<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require_once './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['logout-submit'])) {

    session_unset();
    session_destroy();

    session_start();
    $toast->addMessage("Odhlásenie úspešné", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
