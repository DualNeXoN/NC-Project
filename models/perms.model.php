<?php

namespace Models\Perms {

    use Models\User\Rank as Rank;
    use Models\Perms\PermsConstants as PCo;
    use Models\Perms\PermsQueries as Queries;
    use Models\User\User;
    use Models\User\UserQueries;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/models/user.model.php';

    class PermsHandler {

        const TABLE_PERMS = "perms";

        public static function hasPerms(String $accessKey) {

            if (!isset($_SESSION['id'])) return false;

            $userRankValue = unserialize($_SESSION['user'])->getRank()->getValue();

            return PermsHandler::hasPermsWithRank($userRankValue, $accessKey);
        }

        public static function hasPermsWithRank(int $userRankValue = Rank::DEFAULT_MIN_VALUE, String $accessKey = "unknown") {

            if (!isset($_SESSION['id'])) return false;

            PCo::initMap();
            if (!Queries::existsTable()) Queries::createTable();

            $currentRankId = Queries::getCurrentRankIdOfKey($accessKey);
            if ($currentRankId == -1) return false;

            return Queries::hasAccess($userRankValue, $currentRankId);
        }

        public static function onRankDelete(int $affectedRankId) {
            Queries::resetPermsRankId($affectedRankId);
        }
    }

    class PermsQueries {

        public static function getAllUsersHavingExactPerm(String $key) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $rankId = PermsQueries::getCurrentRankIdOfKey($key);
            $rankRow = UserQueries::getRankRowByRankId($rankId);

            $sql = "SELECT * FROM users WHERE rankValue<=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rankRow['rank']);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            $userArray = [];
            if (mysqli_num_rows($result) > 0) {
                while ($userRow = mysqli_fetch_assoc($result)) {
                    array_push($userArray, new User($userRow['id']));
                }
            }

            return $userArray;
        }

        public static function getCurrentRankIdOfKey(String $key) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM " . PermsHandler::TABLE_PERMS . " WHERE keyName = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                return $row['rankId'];
            }

            return -1;
        }

        public static function hasAccess(int $userRankValue, int $currentRankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT * FROM " . Rank::TABLE_RANK . " WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $currentRankId);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            if (mysqli_num_rows($result) == 0) {
                return false;
            }

            return mysqli_fetch_assoc($result)['rank'] >= $userRankValue;
        }

        public static function existsTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SHOW TABLES LIKE '" . PermsHandler::TABLE_PERMS . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            return (mysqli_num_rows($result) > 0);
        }

        public static function createTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "CREATE TABLE `" . PermsHandler::TABLE_PERMS . "` (
                `keyName` VARCHAR(64) NOT NULL, PRIMARY KEY (`keyName`(64)),
                `rankId` INT(11) NOT NULL DEFAULT '1'
            )";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            Queries::addDefaults();
        }

        public static function addDefaults() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "INSERT INTO `" . PermsHandler::TABLE_PERMS . "` (`keyName`) VALUES";

            $permsMap = PCo::getMap();
            for ($index = 0; $index < count($permsMap); ++$index) {
                if ($index != 0) $sql .= ", ";
                $sql .= "('" . $permsMap[$index] . "')";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();
        }

        public static function resetPermsRankId(int $rankId) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "UPDATE " . PermsHandler::TABLE_PERMS . " SET rankId=default(rankId) WHERE rankId=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $rankId);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
    }

    class PermsConstants {

        const ADMINPANEL_ACCESS = "adminpanel.access";
        const ADMINPANEL_USERS_ACCESS = "adminpanel.users.access";
        const ADMINPANEL_RANKS_ACCESS = "adminpanel.ranks.access";
        const ADMINPANEL_RANKS_ADD = "adminpanel.ranks.add";
        const ADMINPANEL_SERVER_ACCESS = "adminpanel.server.access";
        const ADMINPANEL_SERVER_CONSOLE_ACCESS = "adminpanel.server.console.access";
        const ADMINPANEL_SERVER_ESM_ACCESS = "adminpanel.server.esm.access";
        const ADMINPANEL_SETTINGS_ACCESS = "adminpanel.settings.access";
        const ADMINPANEL_TICKET_ACCESS = "adminpanel.ticket.access";
        const ADMINPANEL_TICKET_OPEN_ANY = "adminpanel.ticket.open.any";
        const ADMINPANEL_TICKET_ASSIGNEE_CHANGE = "adminpanel.ticket.assignee.change";
        const ADMINPANEL_PERMS_ACCESS = "adminpanel.perms.access";
        const ANNOUNCEMENTS_ANNOUNCEMENT_ADD = "announcements.announcement.add";

        public static $map = array();

        public static function initMap() {
            PCo::$map = array();

            PCo::registerPermission(PCo::ADMINPANEL_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_USERS_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_RANKS_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_RANKS_ADD);
            PCo::registerPermission(PCo::ADMINPANEL_SERVER_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_SERVER_CONSOLE_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_SERVER_ESM_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_SETTINGS_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_TICKET_ACCESS);
            PCo::registerPermission(PCo::ADMINPANEL_TICKET_OPEN_ANY);
            PCo::registerPermission(PCo::ADMINPANEL_TICKET_ASSIGNEE_CHANGE);
            PCo::registerPermission(PCo::ADMINPANEL_PERMS_ACCESS);
            PCo::registerPermission(PCo::ANNOUNCEMENTS_ANNOUNCEMENT_ADD);
        }

        private static function registerPermission(String $key) {
            array_push(PCo::$map, $key);
        }

        public static function getMap() {
            return PCo::$map;
        }
    }
}
