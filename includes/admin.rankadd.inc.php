<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['form-submit'])) {

    require_once './dbh.inc.php';

    $rankName = $_POST['rank-name'];
    $rankValue = $_POST['rank-value'];

    $sql = "INSERT INTO ranks (name, rank) VALUES(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $rankName, $rankValue);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Aktualizácia ranku úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminranks');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
