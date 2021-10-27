<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_TICKET_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminTickets/adminTickets.comp.css">

<div class="container init">
    <div class="row justify-content-center">

        <?php
        if (!isset($_GET['ticket-id'])) {
            require './subpages/admin/components/adminTickets/fragments/adminTicketsList/adminTicketsList.frag.php';
        } else {
            require './subpages/admin/components/adminTickets/fragments/adminTicketsDetails/adminTicketsDetails.frag.php';
        }
        ?>

    </div>
</div>