<?php

use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;
use Models\Perms\PermsQueries;
use Models\Ticket\Ticket;
use Models\Ticket\TicketQueries;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

?>

<link rel="stylesheet" href="./subpages/admin/components/adminTickets/fragments/adminTicketsList/adminTicketsList.frag.css">

<div class="container-fluid" style="margin-top: 60px">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>ID</th>
                            <th>Kategória</th>
                            <th>Stav</th>
                            <th>Ticket od</th>
                            <th>Riešiteľ</th>
                            <th>Čas</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require './models/ticket.model.php';
                        $tickets = TicketQueries::getAllTickets();
                        if (mysqli_num_rows($tickets) > 0) {
                            $usersWithAccessToTickets = PermsQueries::getAllUsersHavingExactPerm(PermsConstants::ADMINPANEL_TICKET_ACCESS);
                            $canChangeAssignees = PermsHandler::hasPerms(PermsConstants::ADMINPANEL_TICKET_ASSIGNEE_CHANGE);
                            while ($row = mysqli_fetch_assoc($tickets)) {
                                $ticket = new Ticket($row['id']);
                                $canOpenTicket = canOpenTicket($ticket);
                        ?>
                                <tr class="text-center">
                                    <td>
                                        <?= $ticket->getId() ?>
                                        <input type="hidden" name="ticket-id" value="<?= $ticket->getId() ?>" />
                                    </td>
                                    <td>
                                        <?= $ticket->getTicketIssue()->getIssueLabel() ?>
                                    </td>
                                    <td>
                                        <?= $ticket->getTicketState()->getStateFormatted() ?>
                                    </td>
                                    <td>
                                        <strong><?= $ticket->getUser()->getUsernameFormatted() ?></strong>
                                    </td>
                                    <td>
                                        <strong><?= ($ticket->getAssignee() != null ? $ticket->getAssignee()->getUsernameFormatted() : assignSelf($ticket->getId())) ?></strong>
                                        <?= ($canChangeAssignees ? changeAssignedUser($ticket->getId(), $usersWithAccessToTickets, $ticket->getAssignee(), $ticket->getTicketState()->getId() == 3) : null) ?>
                                    </td>
                                    <td>
                                        <?= $ticket->getCreateTime() ?>
                                    </td>
                                    <td>
                                        <button class="btn <?= ($canOpenTicket ? 'btn-primary' : 'btn-danger') ?> full-width" <?= ($canOpenTicket ? '' : 'disabled') ?> onclick="location.href='./?subpage=admin&component=adminTickets&ticket-id=<?= $ticket->getId() ?>'">Otvoriť</button>
                                    </td>
                                </tr>

                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7">Doposiaľ neboli vytvorené žiadne tickety</td>
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

<?php
function assignSelf(int $ticketId) {
?>
    <div class="col">
        <form action="./includes/admin.ticket.assign.inc.php" method="post">
            <input type="hidden" name="ticket-id" value="<?= $ticketId ?>" />
            <button class="btn btn-primary full-width" type="submit" name="ticket-assign-self">Priradiť sa</button>
        </form>
    </div>
<?php
}

function changeAssignedUser(int $ticketId, array $options, $currentAssignee, bool $ticketIsClosed) {
?>
    <div class="col">
        <a type="button" id="btn-collapse-change-assignee-<?= $ticketId ?>" class="btn btn-primary full-w-h button-change-assignee full-w-h" data-bs-toggle="collapse" data-bs-target="#collapseChangeAssignee-<?= $ticketId ?>" aria-expanded="false" aria-controls="collapseChangeAssignee-<?= $ticketId ?>">Zmeniť riešiteľa</a>
    </div>
    <div class="row justify-content-center" style="margin-top: 8px">
        <div class="col text-center">
            <div class="collapse" id="collapseChangeAssignee-<?= $ticketId ?>">
                <form action="./includes/admin.ticket.assign.inc.php" method="post">
                    <div class="row">
                        <div class="col">
                            <input type="hidden" name="ticket-assign-other" />
                            <input type="hidden" name="ticket-id" value="<?= $ticketId ?>" />
                            <input type="hidden" name="ticket-is-closed" value="<?= ($ticketIsClosed ? 1 : 0) ?>" />
                            <select class="form-select" name="newAssigneeId" onchange="this.form.submit()">
                                <?php
                                if ($currentAssignee == null) {
                                ?>
                                    <option selected value="-1" disabled>Vybrať...</option>
                                <?php
                                }
                                foreach ($options as $option) {
                                ?>
                                    <option <?= ($currentAssignee != null && $currentAssignee->getId() == $option->getId() ? 'selected' : null) ?> value="<?= $option->getId() ?>"><?= $option->getUsername() ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}

function canOpenTicket(Ticket $ticket) {
    if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_TICKET_OPEN_ANY)) return true;
    else return ($ticket->getAssignee() != null && $ticket->getAssignee()->getId() == $_SESSION['id']);
}
?>