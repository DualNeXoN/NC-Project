<?php

namespace Models\User {

    class User {

        public const ONLINE_SECONDS = 0;
        public const ONLINE_MINUTES = 3;

        private int $id;
        private String $username;
        private Rank $rank;

        function __construct(int $id, String $username = null) {
            $this->id = $id;
            if ($username == null) $username = UserQueries::getUsernameById($id);
            $this->setUsername($username);
            $this->rank = new Rank($id);
        }

        public function setUsername(String $username) {
            $this->username = $username;
        }

        public function getUsername() {
            return $this->username;
        }

        public function getId() {
            return $this->id;
        }

        public function getRankName() {
            return (isset($this->rank) ? $this->rank->getName() : Rank::NO_RANK);
        }

        public function getRankColor() {
            return (isset($this->rank) ? $this->rank->getColor() : Rank::DEFAULT_COLOR);
        }

        public function getRank() {
            return $this->rank;
        }

        public function isOnlineOnWeb() {
            return UserQueries::isOnlineOnWeb($this->id);
        }

        public function getUserLink() {
            $rootUrl = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
            return $rootUrl . '?subpage=players&player=' . $this->username;
        }

        public function getAvatarLink(int $size = 40) {
            return 'https://cravatar.eu/avatar/' . $this->username . '/' . $size . '.png';
        }

        public function getUsernameFormatted() {
            return '<span style="color: ' . $this->getRank()->getColor() . '">' . $this->rank->getName() . '</span> &#9679; <span>' . $this->username . '</span>';
        }
    }

    class Rank {

        public const DEFAULT_MIN_VALUE = 50000;
        public const DEFAULT_COLOR = "#ffffff";
        public const NO_RANK = "Rank not defined";
        public const TABLE_RANK = "ranks";

        private int $id;
        private String $name;
        private int $rank;
        private String $color = "#ffffff";

        function __construct(int $userId) {
            $this->loadRank($userId);
        }

        public function loadRank(int $userId) {
            $row = UserQueries::getRankRowByUserId($userId);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->rank = $row['rank'];
            $this->color = $row['color'];
        }

        public function getId() {
            return $this->id;
        }

        public function getName() {
            return $this->name;
        }

        public function getValue() {
            return $this->rank;
        }

        public function getColor() {
            return $this->color;
        }
    }

    class UserQueries {

        public static function getUserById(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return $result;
        }

        public static function getUsernameById(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT username FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result)['username'];
        }

        public static function getPassword(User $user) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';
            $userId = $user->getId();

            $sql = "SELECT pwd FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                return $row['pwd'];
            } else {
                return null;
            }
        }

        public static function getRankRowByUserId(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            UserQueries::correctRankIdForUser($userId);

            $sql = "SELECT rankId FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $rankId = mysqli_fetch_assoc($result)['rankId'];
            $sql = "SELECT * FROM ranks WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rankId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getRankRowByRankId(int $rankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ranks WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rankId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $conn->close();

            return mysqli_fetch_assoc($result);
        }

        public static function getRankIdByUserId(int $userId) {
            return UserQueries::getRankRowByUserId($userId)['id'];
        }

        public static function getRankByUserId(int $userId) {
            return new Rank(UserQueries::getRankIdByUserId($userId));
        }

        public static function isUserHigherRankThanTargetUser(int $currentUserId, int $targetUserId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByUserId($targetUserId);

            return $currentUserRank['rank'] < $targetUserRank['rank'];
        }

        public static function isUserLowerRankThanTargetUser(int $currentUserId, int $targetUserId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByUserId($targetUserId);

            return $currentUserRank['rank'] > $targetUserRank['rank'];
        }

        public static function isUserEqualRankThanTargetUser(int $currentUserId, int $targetUserId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByUserId($targetUserId);

            return $currentUserRank['rank'] == $targetUserRank['rank'];
        }

        public static function isUserHigherRankThanTargetRank(int $currentUserId, int $targetRankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByRankId($targetRankId);

            return $currentUserRank['rank'] < $targetUserRank['rank'];
        }

        public static function isUserLowerRankThanTargetRank(int $currentUserId, int $targetRankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByRankId($targetRankId);

            return $currentUserRank['rank'] > $targetUserRank['rank'];
        }

        public static function isUserEqualRankThanTargetRank(int $currentUserId, int $targetRankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $currentUserRank = UserQueries::getRankRowByUserId($currentUserId);
            $targetUserRank = UserQueries::getRankRowByRankId($targetRankId);

            return $currentUserRank['rank'] == $targetUserRank['rank'];
        }

        public static function correctRankIdForUser(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            //get user rank value
            $sql = "SELECT rankValue FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $userRankValue = mysqli_fetch_assoc($result)['rankValue'];
            $stmt->close();

            //check for lower rank value in ranks table
            $sql = "SELECT * FROM ranks WHERE rank >= " . $userRankValue . " ORDER BY rank ASC LIMIT 1";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            //get rank row
            $rowRank = null;
            if (mysqli_num_rows($result) > 0) {
                $rowRank = $result->fetch_assoc();
            } else {
                //lower rank not found - searching for lowest rank possible
                $sql = "SELECT * FROM ranks ORDER BY rank DESC LIMIT 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();
                $rowRank = $result->fetch_assoc();
            }

            //update rank for user
            $sql = "UPDATE users SET rankId=?, rankValue=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iii", $rowRank['id'], $rowRank['rank'], $userId);
            $stmt->execute();
            $stmt->close();
        }

        public static function getLastActivityTime(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT lastActivityWeb FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 0) return null;
            $stmt->close();
            $conn->close();
            return mysqli_fetch_assoc($result)['lastActivityWeb'];
        }

        public static function updateLastActivityTime(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "UPDATE users SET lastActivityWeb=CURRENT_TIMESTAMP WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }

        public static function isOnlineOnWeb(int $userId) {
            $lastActivity = strtotime(UserQueries::getLastActivityTime($userId));
            $currentTime = time();
            return (($currentTime - $lastActivity) < (User::ONLINE_SECONDS + User::ONLINE_MINUTES * 60));
        }

        public static function isOnlineOnServer(int $userId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT onlineServer FROM users WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if (mysqli_num_rows($result) == 0) return 0;
            $stmt->close();
            $conn->close();
            return mysqli_fetch_assoc($result)['onlineServer'] == 1;
        }
    }
}
