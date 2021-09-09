<link rel="stylesheet" href="./subpages/admin/templates/adminMenu.template.css">

<div class="container-fluid">

    <div class="row justify-content-center" style="margin-top: 60px">
        <div class="col text-center">
            <h2 class="text-outline" style="color: white">Admin panel</h2>
        </div>
    </div>

    <div class="row justify-content-center admin-menu-buttons">
        <div class="text-center col-10 col-sm-8 col-md-6 col-lg-5 col-xl-4">
            <?php

            use Models\Perms\PermsConstants;
            use Models\Perms\PermsHandler;

            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_USERS_ACCESS)) echo '
            <div class="col">
                <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminUsers">Administrácia používateľov</a>
            </div>
            ';

            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_RANKS_ACCESS)) echo '
            <div class="col">
                <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminRanks">Administrácia rankov</a>
            </div>
            ';

            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_ACCESS)) echo '
            <div class="col">
                <a class="btn btn-primary full-width" href="">Administrácia servera</a>
            </div>
            ';

            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_TICKETS_ACCESS)) echo '
            <div class="col">
                <a class="btn btn-primary full-width" href="">Tickety</a>
            </div>
            ';

            if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_PERMS_ACCESS)) echo '
            <div class="col">
                <a class="btn btn-primary full-width" href="./?subpage=admin&component=adminPerms">Práva</a>
            </div>
            ';

            ?>
        </div>
    </div>

</div>