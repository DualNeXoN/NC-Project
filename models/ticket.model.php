<?php

namespace Models\Ticket {

    use Models\User\User;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/models//user.model.php';

    class Ticket {

        private int $id;
        private ?TicketState $ticketState = null;
        private ?TicketIssue $ticketIssue = null;
        private ?TicketMessages $ticketMessages = null;
        private ?User $user = null;
        private ?User $assignee = null;
        private String $createTime;

        function __construct(int $id) {
            $this->id = $id;
            $this->_loadTicket();
        }

        private function _loadTicket() {
            $row = TicketQueries::getExactTicketById($this->id);

            $this->ticketState = new TicketState($row['ticketStateId']);
            $this->ticketIssue = new TicketIssue($row['ticketIssueId']);
            $this->ticketMessages = new TicketMessages($this->id);
            $this->user = new User($row['userId']);
            if ($row['assigneeId'] != null) $this->assignee = new User($row['assigneeId']);
            $this->createTime = $row['createTime'];
        }

        public function getId() {
            return $this->id;
        }

        public function getTicketState() {
            return $this->ticketState;
        }

        public function getTicketIssue() {
            return $this->ticketIssue;
        }

        public function getTicketMessages() {
            return $this->ticketMessages;
        }

        public function getUser() {
            return $this->user;
        }

        public function getAssignee() {
            return $this->assignee;
        }

        public function getCreateTime() {
            return $this->createTime;
        }

        public function getTicketLink() {
            $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
            return $rootUrl . '?subpage=tickets&ticket-id=' . $this->id;
        }
    }

    class TicketState {

        private int $id;
        private String $state;
        private String $color;

        function __construct(int $id) {
            $this->id = $id;
            $this->_loadTicketState();
        }

        private function _loadTicketState() {
            $row = TicketQueries::getExactTicketStateById($this->id);

            $this->state = $row['state'];
            $this->color = $row['color'];
        }

        public function getId() {
            return $this->id;
        }

        public function getState() {
            return $this->state;
        }

        public function getColor() {
            return $this->color;
        }

        public function getStateFormatted() {
            return '<span style="color: ' . $this->color . '">&#9679; </span><span>' . $this->state . '</span><span style="color: ' . $this->color . '"> &#9679;</span><span>';
        }
    }

    class TicketIssue {

        private int $id;
        private String $issueLabel;
        private ?String $issueDesc = null;

        function __construct(int $id) {
            $this->id = $id;
            $this->_loadTicketIssue();
        }

        private function _loadTicketIssue() {
            $row = TicketQueries::getExactTicketIssueById($this->id);

            $this->issueLabel = $row['issueLabel'];
            $this->issueDesc = $row['issueDesc'];
        }

        public function getId() {
            return $this->id;
        }

        public function getIssueLabel() {
            return $this->issueLabel;
        }

        public function getIssueDesc() {
            return $this->issueDesc;
        }
    }

    class TicketMessages {

        private int $ticketId;
        private $messages;

        function __construct(int $ticketId) {
            $this->ticketId = $ticketId;
            $this->_loadMessages();
        }

        private function _loadMessages() {
            $this->messages = [];
            $messagesList = TicketQueries::getAllMessagesOfTicket($this->ticketId);
            while ($row = mysqli_fetch_assoc($messagesList)) {
                array_push($this->messages, new TicketMessage($row['id']));
            }
        }

        public function getTicketId() {
            return $this->ticketId;
        }

        public function getMessages() {
            return $this->messages;
        }
    }

    class TicketMessage {

        private int $id;
        private int $ticketId;
        private int $userId;
        private User $user;
        private String $message;
        private String $time;

        function __construct(int $id) {
            $this->id = $id;
            $this->_loadTicketMessage();
        }

        private function _loadTicketMessage() {
            $row = TicketQueries::getExactMessageById($this->id);

            $this->ticketId = $row['ticketId'];
            $this->userId = $row['userId'];
            $this->message = $row['message'];
            $this->time = $row['time'];

            $this->user = new User($this->userId);
        }

        public function getId() {
            return $this->id;
        }

        public function getTicketId() {
            return $this->ticketId;
        }

        public function getUserId() {
            return $this->userId;
        }

        public function getUser() {
            return $this->user;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getTime() {
            return $this->time;
        }
    }

    class TicketQueries {

        public const MESSAGES_ORDER_OLDEST_FIRST = "ASC";
        public const MESSAGES_ORDER_NEWEST_FIRST = "DESC";

        public static function getAllIssues() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ticket_issues";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function createTicket(int $ticketIssueId, int $userId, String $message) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "INSERT INTO tickets (ticketStateId, ticketIssueId, userId) VALUES (1, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $ticketIssueId, $userId);
            $stmt->execute();

            $newTicketId = mysqli_insert_id($conn);

            $stmt->close();
            $conn->close();

            TicketQueries::addMessageToTicket($newTicketId, $userId, $message);
        }

        public static function addMessageToTicket(int $ticketId, int $userId, String $message) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "INSERT INTO ticket_messages (ticketId, userId, message) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iis", $ticketId, $userId, $message);
            $stmt->execute();

            $stmt->close();
            $conn->close();
        }

        public static function getAllTickets() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM tickets";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getTicketsCreatedByUser(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM tickets WHERE userId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getTicketsAssigneeToUser(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM tickets WHERE assigneeId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getAllMessagesOfTicket(int $ticketId, String $orderLogic = TicketQueries::MESSAGES_ORDER_OLDEST_FIRST) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ticket_messages WHERE ticketId=? ORDER BY time " . $orderLogic;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ticketId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getExactTicketById(int $ticketId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM tickets WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ticketId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getExactTicketStateById(int $ticketStateId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ticket_state WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ticketStateId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getExactTicketIssueById(int $ticketIssueId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ticket_issues WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $ticketIssueId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getExactMessageById(int $messageId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ticket_messages WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $messageId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result);
        }
    }
}
