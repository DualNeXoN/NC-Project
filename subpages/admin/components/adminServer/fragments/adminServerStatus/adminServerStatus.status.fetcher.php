<?php

use MinecraftServerStatus\MinecraftServerStatus;
use Settings\Settings;
use Settings\SettingsConstants as SCo;

require $_SERVER['DOCUMENT_ROOT'] . '/models/minecraft/MinecraftServerStatus.model.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/settings.model.php';

$response = MinecraftServerStatus::query(Settings::getExactSetting(SCo::SERVER_IP), intval(Settings::getExactSetting(SCo::SERVER_PORT)));

echo '<div class="col-10 col-md-6 col-xl-4 text-center" style="background-color: #333333; border: 1px solid grey; padding: 8px">';

if (!$response) {
?>
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <img src="./images/server_offline.png" style="max-width: 80px; min-width: 80px; height: auto; border: 1px solid white; border-radius: 5px"></img>
        </div>
        <div class="col-12 text-center">
            <span style="color: #ff2222; font-size: 28px; font-weight: bold">Server offline</span>
        </div>
    </div>
<?php
} else {
?>
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <img src="<?= $response['favicon'] ?>" style="max-width: 80px; min-width: 80px; height: auto; border: 1px solid white; border-radius: 5px"></img>
        </div>
        <div class="col-12 text-center">
            <span style="color: green; font-size: 28px; font-weight: bold">Server online</span>
        </div>
        <div class="col-12 text-center">
            <span style="font-size: 22px"><?= $response['version'] ?></span>
        </div>
        <div class="col-12 text-center">
            <span style="font-size: 22px"><?= $response['players'] ?> / <?= $response['max_players'] ?></span>
        </div>
        <div class="col-12 text-center">
            <span style="font-size: 22px"><?= $response['ping'] ?>ms</span>
        </div>
    </div>
<?php
}

echo '</div>';
