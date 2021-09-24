<?php

require_once './subpages/head/head.php';
require_once './subpages/navbar/navbar.php';

use Utils\Toast\ToastHandler as Toast;

require_once './utils/toast.util.php';
require_once './utils/activity.util.php';

if (isset($_GET['subpage'])) {

    $subpage = $_GET['subpage'];
    $fullPath = "./subpages/" . $subpage . "/" . $subpage . ".php";
    if (file_exists($fullPath)) {
        require $fullPath;
    } else {
        $toast = unserialize($_SESSION['toast']);
        $toast->addMessage("Podstránka sa nenašla", Toast::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);
        echo ("<script>location.href = './';</script>");
        exit();
    }
} else {
    require './subpages/home/home.php';
}

require_once './subpages/footer/footer.php';

$toast = unserialize($_SESSION['toast']);
$toast->process();
$_SESSION['toast'] = serialize($toast);
