<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsHandler as PermsHandler;
use Models\Perms\PermsConstants as PermsConstants;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!isset($_SESSION['user'])) {

    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

require_once './models/user.model.php';
$rank = unserialize($_SESSION['user'])->getRank()->getValue();

if (PermsHandler::hasPerms(PermsConstants::ADMINPANEL_ACCESS)) {

    if (isset($_GET['component'])) {
        $component = $_GET['component'];
        $path = './subpages/admin/components/' . $component . '/' . $component . '.comp.php';
        if (file_exists($path)) {
            require_once $path;
        } else {
            $toast->addMessage("Komponent webu sa nenašiel", Toast::SEVERITY_ERROR);
            $_SESSION['toast'] = serialize($toast);
            echo ("<script>location.href = './?subpage=admin';</script>");
        }
    } else {
        require_once 'templates/adminMenu.template.php';
    }
} else {

    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}
