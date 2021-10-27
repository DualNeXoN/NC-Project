<link rel="stylesheet" href="./subpages/admin/components/adminTickets/fragments/adminTicketsDetails/adminTicketsDetails.frag.css">

<?php

use Models\Ticket\Ticket;

require_once './models/ticket.model.php';

$ticket = new Ticket($_GET['ticket-id']);
$ticketMessages = $ticket->getTicketMessages();
$ticketIsOpen = $ticket->getTicketState()->getId() == 2;
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
                    if ($ticketIsOpen) {
                    ?>
                        <div class="col-lg-8 col-md-6">
                            <textarea class="full-w-h" id="ticket-message-input" <?= ($ticketIsOpen ? '' : 'disabled') ?>></textarea>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <button class="btn btn-<?= ($ticketIsOpen ? 'primary' : 'danger') ?> full-w-h" <?= ($ticketIsOpen ? 'onclick="sendTicketMessage(' . $ticket->getId() . ')"' : 'disabled') ?> id="ticket-message-send-button">
                                <?= ($ticketIsOpen ? 'Odoslať' : 'Nemožno odoslať správu') ?>
                            </button>
                        </div>
                    <?php
                    }
                    ?>
                    <div class="col">
                        <button class="btn btn-<?= ($ticketIsOpen || $ticketIsClosed ? 'success' : 'danger') ?> full-w-h" <?= ($ticketIsOpen ? '' : 'disabled') ?> data-bs-toggle="modal" data-bs-target="#confirmTicketCloseModal">
                            <?php
                            if ($ticketIsOpen) echo 'Uzavrieť ticket';
                            else if ($ticketIsClosed) echo 'Ticket bol uzavretý <i class="fas fa-check"></i>';
                            else echo 'Nemožno uzavrieť ticket';
                            ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirmTicketCloseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col text-center modal-account-username">Naozaj chceš uzavrieť ticket?</div>
                    </div>
                </div>
                <div class="container">
                    <div class="row justify-content-around choice-container">
                        <div class="col-4 text-center">
                            <form action="./includes/admin.ticket.close.inc.php" method="post" class="full-w-h">
                                <input type="hidden" name="ticket-id" value="<?= $ticket->getId() ?>" />
                                <button type="submit" class="btn btn-success full-w-h" name="ticket-close-submit">Áno</button>
                            </form>
                        </div>
                        <div class="col-4 text-center">
                            <button class="btn btn-danger full-w-h" onclick="closeModal()">Nie</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./subpages/admin/components/adminTickets/fragments/adminTicketsDetails/adminTicketsDetails.frag.js"></script>

<script>
    scrollToLatestMessage(500);
</script>

<script>
    function closeModal() {
        $('#confirmTicketCloseModal').modal('hide');
    }
</script>