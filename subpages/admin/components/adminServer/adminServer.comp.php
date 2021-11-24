<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminServer/adminServer.comp.css">

<div class="container-fluid" style="margin-top: 60px">

    <?php
    require './subpages/admin/components/adminServer/fragments/adminServerStatus/adminServerStatus.frag.php';

    if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_CONSOLE_ACCESS)) {
        require './subpages/admin/components/adminServer/fragments/adminConsole/adminConsole.frag.php';
    }

    if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_ESM_ACCESS)) {
        require './subpages/admin/components/adminServer/fragments/adminESM/adminESM.frag.php';
    }
    ?>

</div>