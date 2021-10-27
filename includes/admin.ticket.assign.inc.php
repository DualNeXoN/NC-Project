<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['ticket-assign-self'])) {

    require_once './dbh.inc.php';

    $ticketNewState = 2;
    $ticketId = $_POST['ticket-id'];
    $assigneeId = $_SESSION['id'];

    $sql = "UPDATE tickets SET ticketStateId=?, assigneeId=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $ticketNewState, $assigneeId, $ticketId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Bol si priradený k ticketu s ID " . $ticketId, Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminTickets');
    exit();
} else if (isset($_POST['ticket-assign-other'])) {

    require_once './dbh.inc.php';

    $ticketNewState = ($_POST['ticket-is-closed'] == 1 ? 3 : 2);
    $ticketId = $_POST['ticket-id'];
    $assigneeId = $_POST['newAssigneeId'];

    $sql = "UPDATE tickets SET ticketStateId=?, assigneeId=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $ticketNewState, $assigneeId, $ticketId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Aktualizácia riešiteľa ticketu s ID " . $ticketId . " úspešná", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    header('Location: ./../?subpage=admin&component=adminTickets');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
