<?php

$serverName = "localhost";
$dbUser = "root";
$dbPwd = "";
$dbName = "mc";

$conn = mysqli_connect($serverName, $dbUser, $dbPwd, $dbName);
if (!$conn) {
    die("CONNECTION FAILED: " . mysqli_connect_error());
}
