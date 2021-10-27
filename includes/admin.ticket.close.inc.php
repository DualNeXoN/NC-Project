<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (isset($_POST['ticket-close-submit'])) {

    require_once './dbh.inc.php';

    $ticketNewState = 3;
    $ticketId = $_POST['ticket-id'];

    $sql = "UPDATE tickets SET ticketStateId=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $ticketNewState, $ticketId);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    $toast->addMessage("Ticket s ID " . $ticketId . " bol uzavretý", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);

    echo ('<script>location.href="./../?subpage=admin&component=adminTickets&ticket-id=' . $ticketId . '"</script>');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../');
    exit();
}
