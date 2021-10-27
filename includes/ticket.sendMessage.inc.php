<?php

use Models\Ticket\TicketQueries;

session_start();

require './../models/ticket.model.php';

if (!isset($_SESSION['id'])) {
    header('Location: ./../');
    exit();
}

if (isset($_POST['ticketId'])) {
    if (isset($_POST['message']) && strlen($_POST['message']) > 0) {
        TicketQueries::addMessageToTicket($_POST['ticketId'], $_SESSION['id'], $_POST['message']);
        return;
    }
    return;
} else {
    header('Location: ./../?subpage=tickets');
    exit();
}
