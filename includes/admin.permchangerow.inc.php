<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require_once './dbh.inc.php';

    $permNewRankId = $_POST['rank-selected'];
    $permKeyName = $_POST['perm-selected'];

    $sql = "UPDATE perms SET rankId=? WHERE keyName=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $permNewRankId, $permKeyName);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Aktualizácia práva úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminPerms');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
