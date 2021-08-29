<?php

namespace Models\User {

    class User {

        private int $id;
        private String $username;
        private Rank $rank;

        function __construct(int $id, String $username, int $rankId) {
            $this->id = $id;
            $this->setUsername($username);
            $this->rank = new Rank($rankId);
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

        private String $name;
        private int $rank;
        private int $colorRed = 255;
        private int $colorGreen = 255;
        private int $colorBlue = 255;

        function __construct(int $userRankId) {
            if (!$this->existsTable()) $this->initTable();
            $this->loadRank($userRankId);
        }

        private function existsTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SHOW TABLES LIKE '" . RANK::TABLE_RANK . "'";
            $result = $conn->query($sql);
            $conn->close();
            return (mysqli_num_rows($result) > 0);
        }

        private function initTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "CREATE TABLE " . RANK::TABLE_RANK . " (
                id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(32) NOT NULL,
                rank INT(11) NOT NULL,
                colorRed INT(11) NOT NULL DEFAULT 255,
                colorGreen INT(11) NOT NULL DEFAULT 255,
                colorBlue INT(11) NOT NULL DEFAULT 255
            )";
            $conn->query($sql);

            $sql = "INSERT INTO ranks (name, rank, colorRed, colorGreen, colorBlue) VALUES('Hráč', 10000, 255, 255, 255), ('Admin', 100, 29, 132, 171)";
            $conn->query($sql);

            $conn->close();
        }

        public function loadRank(int $userRankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM ranks WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userRankId);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            $row = mysqli_fetch_assoc($result);
            $this->name = $row['name'];
            $this->rank = $row['rank'];
            $this->colorRed = $row['colorRed'];
            $this->colorGreen = $row['colorGreen'];
            $this->colorBlue = $row['colorBlue'];
        }

        public function getName() {
            return $this->name;
        }

        public function getValue() {
            return $this->rank;
        }

        public function getColor() {
            return "rgb(" . $this->colorRed . "," . $this->colorGreen . "," . $this->colorBlue . ")";
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
    }
}
