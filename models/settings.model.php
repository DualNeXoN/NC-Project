<?php

namespace Settings {

    use Settings\SettingsConstants as SCo;

    class Settings {

        const TABLE_SETTINGS = "settings";
        const VALUE_INIT = "undefined";

        public static function getExactSetting(String $key) {
            if (!Settings::existsTable()) Settings::createTable();

            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT value FROM " . Settings::TABLE_SETTINGS . " WHERE keyName=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $key);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result)['value'];
        }

        public static function existsTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SHOW TABLES LIKE '" . Settings::TABLE_SETTINGS . "'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $conn->close();

            return (mysqli_num_rows($result) > 0);
        }

        public static function createTable() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "CREATE TABLE `" . Settings::TABLE_SETTINGS . "` (
                `keyName` VARCHAR(64) NOT NULL, PRIMARY KEY (`keyName`(64)),
                `value` VARCHAR(64) NOT NULL DEFAULT '" . Settings::VALUE_INIT . "'
            )";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();
            $conn->close();

            Settings::addDefaults();
        }

        public static function addDefaults() {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "INSERT INTO `" . Settings::TABLE_SETTINGS . "` (`keyName`) VALUES";

            SCo::initMap();
            $permsMap = SCo::getMap();
            for ($index = 0; $index < count($permsMap); ++$index) {
                if ($index != 0) $sql .= ", ";
                $sql .= "('" . $permsMap[$index] . "')";
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stmt->close();
        }
    }

    class SettingsConstants {

        const RCON_PWD = "rcon.pwd";

        public static $map = array();

        public static function initMap() {
            SCo::$map = array();

            SCo::registerSetting(SCo::RCON_PWD);
        }

        private static function registerSetting(String $key) {
            array_push(SCo::$map, $key);
        }

        public static function getMap() {
            return SCo::$map;
        }
    }
}
