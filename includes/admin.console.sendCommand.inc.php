<?php

use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;
use Thedudeguy\Rcon;
use Settings\Settings;
use Settings\SettingsConstants as SCo;

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/models/perms.model.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/settings.model.php';

if (isset($_SESSION['id']) && PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SERVER_CONSOLE_ACCESS)) {
    require './../models/minecraft/Rcon.model.php';
    $command = $_POST['cmd'];

    $host = 'dev.dualnexon.sk';
    $port = 25575;
    $password = Settings::getExactSetting(SCo::RCON_PWD);
    $timeout = 5;

    $rcon = new Rcon($host, $port, $password, $timeout);

    if ($rcon->connect()) {
        $rcon->sendCommand($command);
        echo $rcon->getResponse();
        $rcon->disconnect();
    } else {
        echo "[ERROR] Connection to console was denied!";
    }
}
