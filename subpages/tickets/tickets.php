<?php

use Utils\Toast\ToastHandler as Toast;

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!isset($_SESSION['id'])) {

    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/tickets/tickets.css">

<div class="container init">
    <div class="row justify-content-center">

        <?php
        if (!isset($_GET['ticket-id'])) {

        ?>
            <div class="row justify-content-center divider-top">
                <div class="col text-center fragment-label">
                    Tickety
                </div>
            </div>
        <?php

            require './subpages/tickets/fragments/ticketNew/ticketNew.frag.php';
            require './subpages/tickets/fragments/ticketList/ticketList.frag.php';
        } else {
            require './subpages/tickets/fragments/ticketDetails/ticketDetails.frag.php';
        }
        ?>

    </div>
</div>