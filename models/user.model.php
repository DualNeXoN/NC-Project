<?php

namespace Models\User {

    class User {

        private int $id;
        private String $username;
        private Rank $rank;

        function __construct(int $id, String $username) {
            $this->id = $id;
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

        public function getRank() {
            return $this->rank;
        }
    }

    class Rank {

        public const NO_RANK = "Rank not defined";
        public const TABLE_RANK = "ranks";

        private int $id;
        private String $name;
        private int $rank;
        private String $color = "#ffffff";

        function __construct(int $userId) {
            if (!$this->existsTable()) $this->initTable();
            $this->loadRank($userId);
        }

        public static function existsTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SHOW TABLES LIKE '" . RANK::TABLE_RANK . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();
            return (mysqli_num_rows($result) > 0);
        }

        public static function initTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "CREATE TABLE " . RANK::TABLE_RANK . " (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(32) NOT NULL,
                rank INT(11) NOT NULL,
                color VARCHAR(7) NOT NULL DEFAULT '#ffffff'
            )";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();

            $sql = "INSERT INTO ranks (name, rank) VALUES('Hráč', 50000), ('Admin', 0)";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();

            //correct all rank ids for users
            $sql = "SELECT id FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    UserQueries::correctRankIdForUser($row['id']);
                }
            }

            $conn->close();
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

        public static function getRankIdByUserId(int $userId) {
            return UserQueries::getRankRowByUserId($userId)['id'];
        }

        public static function getRankByUserId(int $userId) {
            return new Rank(UserQueries::getRankIdByUserId($userId));
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
    }
}
