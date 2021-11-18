<?php

if (isset($_POST['submit-like'])) {

    $userId = $_POST['userId'];
    $announcementId = $_POST['announcementId'];
    $reactionsId = $_POST['reactionsId'];

    require './dbh.inc.php';

    $sql = "SELECT * FROM announcement_reactions WHERE userId=? AND announcementId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $announcementId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 0) {
        $sql = "INSERT INTO announcement_reactions (userId, announcementId, reactionsId) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $announcementId, $reactionsId);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
} else if (isset($_POST['submit-like-undo'])) {

    $userId = $_POST['userId'];
    $announcementId = $_POST['announcementId'];
    $reactionsId = $_POST['reactionsId'];

    require './dbh.inc.php';

    $sql = "SELECT * FROM announcement_reactions WHERE userId=? AND announcementId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $userId, $announcementId);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) == 1) {
        $sql = "DELETE FROM announcement_reactions WHERE userId=? AND announcementId=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $announcementId);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();
}
