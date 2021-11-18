<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require './dbh.inc.php';

    $announcementId = $_POST['announcementId'];

    $sql = "DELETE FROM announcement_reactions WHERE announcementId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $announcementId);
    $stmt->execute();

    $sql = "DELETE FROM announcements WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $announcementId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo ('<script>location.href="./../?subpage=announcements"</script>');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../?subpage=announcements');
    exit();
}
