<?php

namespace Models\Announcement {

    use Models\Time\TimeConverter;
    use Models\User\User;
    use Models\User\UserQueries;

    require_once './models/user.model.php';
    require_once './models/time.model.php';

    class Announcement {

        private int $id;
        private String $title;
        private String $message;
        private String $createTimestamp;
        private ?String $editTimestamp = null;
        private ?User $user = null;
        private int $visible;
        private int $reactionCount;

        function __construct(int $id) {
            $this->_loadData($id);
        }

        private function _loadData(int $id) {
            $row = AnnouncementQueries::getExactAnnouncementById($id);
            $this->id = $row['id'];
            $this->title = $row['title'];
            $this->message = $row['message'];
            $this->createTimestamp = $row['date_create'];
            if ($row['date_edit'] != null) $this->editTimestamp = $row['date_edit'];
            $this->user = new User($row['userId']);
            $this->visible = $row['visible'];
            $this->reactionCount = AnnouncementQueries::getAnnouncementReactionCountById($id);
        }

        public function getId() {
            return $this->id;
        }

        public function getTitle() {
            return $this->title;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getCreateTimestamp() {
            return $this->createTimestamp;
        }

        public function getCreateDay() {
            return TimeConverter::convertMySQLTimestamp($this->createTimestamp, TimeConverter::DAY_MONTH_YEAR_DATE_FORMAT);
        }

        public function getCreateTime() {
            return TimeConverter::convertMySQLTimestamp($this->createTimestamp, TimeConverter::HOUR_MINUTE_SECOND_DATE_FORMAT);
        }

        public function getEditTimestamp() {
            return $this->editTimestamp;
        }

        public function getEditDay() {
            if ($this->editTimestamp == null) return null;
            return TimeConverter::convertMySQLTimestamp($this->editTimestamp, TimeConverter::DAY_MONTH_YEAR_DATE_FORMAT);
        }

        public function getEditTime() {
            if ($this->editTimestamp == null) return null;
            return TimeConverter::convertMySQLTimestamp($this->editTimestamp, TimeConverter::HOUR_MINUTE_SECOND_DATE_FORMAT);
        }

        public function getUser() {
            return $this->user;
        }

        public function isVisible() {
            return $this->visible == 1;
        }

        public function canLoggedUserEditAnnouncement() {
            if (!isset($_SESSION['id'])) return false;
            if ($_SESSION['id'] == $this->user->getId()) return true;
            $currentUserLoggedIn = unserialize($_SESSION['user']);
            return UserQueries::isUserHigherRankThanTargetUser($currentUserLoggedIn->getId(), $this->user->getId());
        }

        public function getReactionCount() {
            return $this->reactionCount;
        }

        public function hasLoggedUserReacted() {
            if (!isset($_SESSION['id'])) return false;
            return mysqli_num_rows(AnnouncementQueries::getExactAnnouncementReactionByUserId($this->id, $_SESSION['id'])) > 0;
        }
    }

    class AnnouncementQueries {

        public const ANNOUNCEMENTS_ORDER_OLDEST_FIRST = "ASC";
        public const ANNOUNCEMENTS_ORDER_NEWEST_FIRST = "DESC";

        public static function getAllAnnouncements(String $orderLogic = AnnouncementQueries::ANNOUNCEMENTS_ORDER_NEWEST_FIRST) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM announcements ORDER BY date_create " . $orderLogic;
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getExactAnnouncementById(int $id) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM announcements WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getAnnouncementReactionCountById(int $id) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT COUNT(id) AS reactionCount FROM announcement_reactions WHERE announcementId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result)['reactionCount'];
        }

        public static function getExactAnnouncementReactionByUserId(int $announcementId, int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM announcement_reactions WHERE announcementId=? AND userId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $announcementId, $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }
    }
}
