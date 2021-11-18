<?php

namespace Models\Time {

    class TimeConverter {

        public const FULL_DATE_FORMAT = "%d.%c. %Y %H:%i:%s";
        public const DAY_MONTH_YEAR_DATE_FORMAT = "%d.%c. %Y";
        public const HOUR_MINUTE_SECOND_DATE_FORMAT = "%H:%i:%s";

        public static function convertMySQLTimestamp(String $timestamp, String $format = TimeConverter::FULL_DATE_FORMAT) {
            require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';

            $sql = "SELECT DATE_FORMAT(?, ?) AS result";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ss', $timestamp, $format);
            $stmt->execute();
            $result = $stmt->get_result();

            $stmt->close();
            $conn->close();

            return mysqli_fetch_assoc($result)['result'];
        }
    }
}
