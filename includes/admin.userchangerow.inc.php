<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require_once './dbh.inc.php';

    $userNewRankId = $_POST['rank-selected'];
    $userId = $_POST['user-selected'];

    $sql = "SELECT rank FROM ranks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userNewRankId);
    $stmt->execute();
    $result = $stmt->get_result();
    $rankValue = mysqli_fetch_assoc($result)['rank'];

    $sql = "UPDATE users SET rankId=?, rankValue=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userNewRankId, $rankValue, $userId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Aktualizácia účtu úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminusers');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
