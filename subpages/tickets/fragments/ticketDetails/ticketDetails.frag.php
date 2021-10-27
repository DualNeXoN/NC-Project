<link rel="stylesheet" href="./subpages/tickets/fragments/ticketDetails/ticketDetails.frag.css">

<?php

use Models\Ticket\Ticket;

require_once './models/ticket.model.php';

$ticket = new Ticket($_GET['ticket-id']);
$ticketMessages = $ticket->getTicketMessages();
$ticketCanReply = $ticket->getTicketState()->getId() == 2;
$ticketIsClosed = $ticket->getTicketState()->getId() == 3;
$_SESSION['ticket-details-id'] = $ticket->getId();
?>

<div class="row justify-content-center divider-top">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12 text-center">
            <div class="ticket-card">
                <div class="row justify-content-center">
                    <div class="col">
                        Ticket #<?= $ticket->getId() ?>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col">
                        <span style="color: <?= $ticket->getTicketState()->getColor() ?>">
                            <strong> &#9679;</strong>
                        </span>
                        <?= $ticket->getTicketState()->getState() ?>
                        <span style="color: <?= $ticket->getTicketState()->getColor() ?>">
                            <strong> &#9679;</strong>
                        </span>
                    </div>
                </div>
                <?php
                if ($ticket->getAssignee() != null) {
                ?>

                    <div class="row justify-content-center">
                        <div class="col">
                            Riešiteľ: <?= $ticket->getAssignee()->getUsernameFormatted() ?>
                        </div>
                    </div>

                <?php
                }
                ?>
                <div class="row messages-card overflow-auto" id="messages">
                    <div class="col" id="messages-container"></div>
                </div>
                <div class="row reply-card justify-content-center">
                    <?php
                    if (!$ticketIsClosed) {
                    ?>
                        <div class="col-xl-10 col-md-8">
                            <textarea class="full-w-h" id="ticket-message-input" <?= ($ticketCanReply ? '' : 'disabled') ?>></textarea>
                        </div>
                        <div class="col-xl-2 col-md-4">
                            <button class="btn btn-<?= ($ticketCanReply ? 'primary' : 'danger') ?> full-w-h" <?= ($ticketCanReply ? 'onclick="sendTicketMessage(' . $ticket->getId() . ')"' : 'disabled') ?> id="ticket-message-send-button">
                                <?= ($ticketCanReply ? 'Odoslať' : 'Čakanie na odpoveď') ?>
                            </button>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="col">
                            <button class="btn btn-success full-w-h" disabled>Ticket bol uzavretý <i class="fas fa-check"></i></button>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./subpages/tickets/fragments/ticketDetails/ticketDetails.frag.js"></script>

<script>
    scrollToLatestMessage(500);
</script>