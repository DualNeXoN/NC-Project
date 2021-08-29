<?php

const MIN_PERMS_ADMINISTRATION = 100;

if (!isset($_SESSION['user'])) return;

require_once './models/user.model.php';
$rank = unserialize($_SESSION['user'])->getRank()->getValue();

if ($rank <= MIN_PERMS_ADMINISTRATION) {
    echo '
    <li class="nav-item">
        <a class="nav-link" href="./?subpage=admin">Admin panel</a>
    </li>
    ';
}
