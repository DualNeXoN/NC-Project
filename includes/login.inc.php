<?php

session_start();

use Models\User\User as User;
use Utils\Toast\ToastHandler as Toast;

require_once './../models/user.model.php';
require_once './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['login-submit'])) {

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];

    require_once './dbh.inc.php';

    $sql = "SELECT * FROM users WHERE username=? AND pwd=SHA2(?, 512)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $pwd);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        $username = $row['username'];
        $_SESSION['username'] = $username;

        $id = $row['id'];
        $_SESSION['id'] = $id;

        $userRankId = $row['rankId'];
        $_SESSION['rankId'] = $userRankId;

        $_SESSION['rankValue'] = $row['rankValue'];

        $_SESSION['user'] = serialize(new User($id, $username, $userRankId));
        $toast->addMessage("Prihlásený ako <strong>" . $username . "</strong>", Toast::SEVERITY_SUCCESS);
        $_SESSION['toast'] = serialize($toast);
        header('Location: ./../');
        exit();
    } else {
        $toast->addMessage("Nesprávne prihlasovacie údaje", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);
        header('Location: ./../');
        exit();
    }
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
