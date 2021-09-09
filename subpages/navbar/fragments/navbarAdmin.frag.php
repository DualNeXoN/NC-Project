<?php

use Models\Perms\PermsHandler as PermsHandler;
use Models\Perms\PermsConstants as PermsConstants;

if (!isset($_SESSION['id'])) return;

require_once './models/user.model.php';
require_once './models/perms.model.php';
$rank = unserialize($_SESSION['user'])->getRank()->getValue();

if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_ACCESS)) {
    echo '
    <li class="nav-item">
        <a class="nav-link" href="./?subpage=admin">Admin panel</a>
    </li>
    ';
}
