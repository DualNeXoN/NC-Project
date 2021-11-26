<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require_once './dbh.inc.php';

    $settingSelected = $_POST['setting-selected'];
    $settingNewValue = $_POST['setting-new-value'];

    $sql = "UPDATE settings SET value=? WHERE keyName=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $settingNewValue, $settingSelected);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Aktualizácia premennej úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminSettings');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
