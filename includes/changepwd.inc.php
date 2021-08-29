<?php

session_start();

use Utils\Toast\ToastHandler as Toast;
use Models\User\UserQueries as UserQueries;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);
require './../models/user.model.php';

if (isset($_POST["changepwd-submit"])) {

    $currentPwd = $_POST['current-pwd'];

    if (hash('sha512', $currentPwd) != UserQueries::getPassword(unserialize($_SESSION['user']))) {
        $toast->addMessage("Neplatné aktuálne heslo", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);
        header('Location: ./../');
        exit();
    }

    $newPwd = $_POST['new-pwd'];
    $newPwdRepeat = $_POST['new-pwd-repeat'];

    if ($newPwd != $newPwdRepeat) {
        $toast->addMessage("Heslá sa nezhodujú", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);
        header('Location: ./../');
        exit();
    }

    require './dbh.inc.php';

    $sql = "UPDATE users SET pwd=SHA2(?, 512) WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newPwd, $_SESSION['id']);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Heslo aktualizované", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
