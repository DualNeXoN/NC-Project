<?php

if (isset($_POST['form-submit'])) {

    require './dbh.inc.php';

    $announcementId = $_POST['announcementId'];
    $title = $_POST['title'];
    $message = $_POST['message'];

    $sql = "UPDATE announcements SET title=?, message=?, date_edit=current_timestamp() WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $message, $announcementId);
    $stmt->execute();

    $stmt->close();
    $conn->close();
}
