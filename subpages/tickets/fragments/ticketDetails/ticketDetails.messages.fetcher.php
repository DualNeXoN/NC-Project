<?php

session_start();

use Models\Ticket\Ticket;

require_once $_SERVER['DOCUMENT_ROOT'] . '/models/ticket.model.php';

$ticket = new Ticket($_SESSION['ticket-details-id']);
$ticketMessages = $ticket->getTicketMessages();

foreach ($ticketMessages->getMessages() as $message) {

    $isYourMessage = $message->getUserId() == $_SESSION['id'];
    $user = $message->getUser();

?>

    <div class="d-flex flex-row<?= ($isYourMessage ? '' : '-reverse') ?> text-start">
        <div class="p-2 message <?= ($isYourMessage ? 'message-self' : 'message-other') ?>">
            <?= htmlspecialchars($message->getMessage()) ?>
            <hr>
            <i class="fas fa-user"></i> <strong><?= ($message->getUserId() == $_SESSION['id'] ? 'Ty' : $user->getUsernameFormatted()) ?></strong><br>
            <i class="far fa-clock"></i> <strong><?= $message->getTime() ?></strong>
        </div>
    </div>

<?php

}
