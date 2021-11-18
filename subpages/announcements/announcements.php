<div class="container-fluid" style="padding: 0 0; margin: 50px 0; color: white;">

    <div class="container-fluid">

        <?php

        use Models\Perms\PermsHandler as PermsHandler;
        use Models\Perms\PermsConstants as PermsConstants;

        require_once './models/perms.model.php';

        if (PermsHandler::hasPerms(PermsConstants::ANNOUNCEMENTS_ANNOUNCEMENT_ADD)) {
            require './subpages/announcements/fragments/announcementsNew/announcementsNew.frag.php';
        }
        ?>
        <?php require './subpages/announcements/fragments/announcementsList/announcementsList.frag.php'; ?>

    </div>

</div>