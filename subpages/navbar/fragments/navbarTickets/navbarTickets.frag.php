<?php

if (isset($_SESSION['id'])) {
    echo '
    <li class="nav-item">
        <a class="nav-link" href="./?subpage=tickets">Tickety</a>
    </li>';
}
