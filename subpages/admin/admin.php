<?php

use Utils\Toast\ToastHandler as Toast;

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

if ($rank <= MIN_PERMS_ADMINISTRATION) {

    if (isset($_GET['component'])) {
        $component = $_GET['component'];
        if (file_exists('./subpages/admin/components/' . $component . '/' . $component . '.comp.php')) {
            require_once './subpages/admin/components/' . $component . '/' . $component . '.comp.php';
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
