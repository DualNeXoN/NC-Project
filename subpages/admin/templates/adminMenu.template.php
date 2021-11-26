<link rel="stylesheet" href="./subpages/admin/templates/adminMenu.template.css">

<?php

use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

?>

<div class="container-fluid">

    <div class="row justify-content-center" style="margin-top: 60px">
        <div class="col text-center">
            <h2 class="text-outline" style="color: white">Admin panel</h2>
        </div>
    </div>

    <div class="row justify-content-center admin-menu-buttons">
        <div class="text-center col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4">

            <?php if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_USERS_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminUsers">Administrácia používateľov</a>
                </div>
            <?php }
            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_RANKS_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminRanks">Administrácia rankov</a>
                </div>
            <?php }
            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminServer">Administrácia servera</a>
                </div>
            <?php }
            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_TICKET_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminTickets">Tickety</a>
                </div>
            <?php }
            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_PERMS_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminPerms">Práva</a>
                </div>
            <?php }
            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SETTINGS_ACCESS)) { ?>
                <div class="col">
                    <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminSettings">Administrácia globálnych premenných</a>
                </div>
            <?php } ?>

        </div>
    </div>

</div>