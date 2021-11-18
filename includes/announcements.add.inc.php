<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require './dbh.inc.php';

    $userId = $_SESSION['id'];
    $title = $_POST['new-title'];
    $message = $_POST['new-message'];

    $sql = "INSERT INTO announcements (userId, title, message, date_edit, visible) VALUES(?, ?, ?, current_timestamp(), DEFAULT(visible))";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $userId, $title, $message);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Oznam pridaný", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    echo ('<script>location.href="./../?subpage=announcements"</script>');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../?subpage=announcements');
    exit();
}
