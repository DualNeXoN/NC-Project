<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

use Models\Ticket\TicketQueries as Query;

require './../models/ticket.model.php';

if (isset($_POST['form-submit'])) {

    $ticketIssueId = $_POST['ticket-issue-id'];
    $userId = $_SESSION['id'];
    $message = $_POST['ticket-message'];

    Query::createTicket($ticketIssueId, $userId, $message);

    $toast->addMessage("Ticket vytvorený", Toast::SEVERITY_SUCCESS);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../?subpage=tickets');
    exit();
} else {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);
    header('Location: ./../?subpage=tickets');
    exit();
}
