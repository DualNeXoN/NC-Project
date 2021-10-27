<link rel="stylesheet" href="./subpages/tickets/fragments/ticketList/ticketList.frag.css">

<div class="row justify-content-center text-center">

    <div class="row justify-content-center" style="margin-top: 36px; margin-bottom: 48px">
        <div class="col-11">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>ID</th>
                            <th>Stav ticketu</th>
                            <th>Riešiteľ</th>
                            <th style="width: 20%"></th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

                        use Models\Ticket\Ticket;
                        use Models\Ticket\TicketQueries;

                        require_once './models/ticket.model.php';

                        $tickets = TicketQueries::getTicketsCreatedByUser($_SESSION['id']);
                        if (mysqli_num_rows($tickets) > 0) {
                            while ($row = mysqli_fetch_assoc($tickets)) {

                                $ticket = new Ticket($row['id']);

                        ?>

                                <tr>
                                    <td><?= $ticket->getId() ?></td>
                                    <td>
                                        <span style="color: <?= $ticket->getTicketState()->getColor() ?>">
                                            <strong>&#9679; </strong>
                                        </span>
                                        <?= $ticket->getTicketState()->getState() ?>
                                        <span style="color: <?= $ticket->getTicketState()->getColor() ?>">
                                            <strong> &#9679;</strong>
                                        </span>
                                    </td>
                                    <td><strong><?= ($ticket->getAssignee() != null ? $ticket->getAssignee()->getUsernameFormatted() : '?') ?></strong></td>
                                    <td><a class="btn btn-primary" type="button" href="<?= $ticket->getTicketLink() ?>">Prezrieť ticket</button></td>
                                </tr>

                            <?php
                            }
                        } else {
                            ?>

                            <tr>
                                <td colspan="4">Doposiaľ si nevytvoril žiadny ticket</td>
                            </tr>

                        <?php
                        }
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>