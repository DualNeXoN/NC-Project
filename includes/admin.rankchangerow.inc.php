<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit-save'])) {

    require_once './dbh.inc.php';

    $rankId = $_POST['rank-selected'];
    $rankName = $_POST['rank-name'];
    $rankValue = $_POST['rank-value'];
    $rankColor = $_POST['input-color'];

    $sql = "UPDATE ranks SET name=?, rank=?, color=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sisi", $rankName, $rankValue, $rankColor, $rankId);
    $stmt->execute();
    $stmt->close();

    $sql = "UPDATE users SET rankId=?, rankValue=? WHERE rankId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $rankId, $rankValue, $rankId);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    $toast->addMessage("Aktualizácia ranku úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminranks');
    exit();
} else if (isset($_POST['form-submit-delete'])) {

    require_once './dbh.inc.php';

    $rankId = $_POST['rank-selected'];

    $sql = "UPDATE users SET rankId=DEFAULT(rankId), rankValue=DEFAULT(rankValue) WHERE rankId=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rankId);
    $stmt->execute();
    $stmt->close();

    $sql = "DELETE FROM ranks WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rankId);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    $toast->addMessage("Odstránenie ranku úspešné", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminranks');
    exit();
} else {

    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
