<link rel="stylesheet" href="./subpages/admin/templates/adminMenu.template.css">

<?php

use Models\Perms\PermsConstants as PCo;
use Models\AdminList\AdminListBuilder as ALB;

require_once './models/adminList.model.php';

?>

<div class="container-fluid">

    <div class="row justify-content-center text-center admin-menu-buttons">

        <?php

        ALB::build("Administrácia používateľov", "adminUsers", PCo::ADMINPANEL_USERS_ACCESS, "icon_user.png")->renderWhenHasPerms();
        ALB::build("Administrácia rankov", "adminRanks", PCo::ADMINPANEL_RANKS_ACCESS, "icon_rank.png")->renderWhenHasPerms();
        ALB::build("Administrácia servera", "adminServer", PCo::ADMINPANEL_SERVER_ACCESS, "icon_server.png")->renderWhenHasPerms();
        ALB::build("Tickety", "adminTickets", PCo::ADMINPANEL_TICKET_ACCESS, "icon_ticket.png")->renderWhenHasPerms();
        ALB::build("Práva", "adminPerms", PCo::ADMINPANEL_PERMS_ACCESS, "icon_perms.png")->renderWhenHasPerms();
        ALB::build("Globálne premenné", "adminSettings", PCo::ADMINPANEL_SETTINGS_ACCESS, "icon_settings.png")->renderWhenHasPerms();

        ?>

    </div>

</div>