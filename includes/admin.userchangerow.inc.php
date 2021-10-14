<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

use Models\User\UserQueries as UserQuery;

require './../models/user.model.php';

if (isset($_POST['form-submit'])) {

    $userId = $_POST['user-selected'];

    if (UserQuery::isUserLowerRankThanTargetUser($_SESSION['id'], $userId)) {
        $toast->addMessage("Nemáš právo na zmenu ranku pre hráča vyššieho postavenia", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);

        header('Location: ./../?subpage=admin&component=adminusers');
        exit();
    }

    $userNewRankId = $_POST['rank-selected'];

    if (UserQuery::isUserLowerRankThanTargetRank($_SESSION['id'], $userNewRankId)) {
        $toast->addMessage("Nemáš právo zmeniť rank na vyšší, než si ty", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);

        header('Location: ./../?subpage=admin&component=adminusers');
        exit();
    }

    require './dbh.inc.php';

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
