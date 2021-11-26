<?php

require $_SERVER['DOCUMENT_ROOT'] . '/models/minecraft/MinecraftPing.model.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/minecraft/MinecraftPingException.model.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/playerESM.model.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/settings.model.php';

use xPaw\MinecraftPing;
use xPaw\MinecraftPingException;
use Models\PlayerESM\PlayerESM;
use Settings\Settings;
use Settings\SettingsConstants as SCo;

$query = null;
try {
    $query = new MinecraftPing(Settings::getExactSetting(SCo::SERVER_IP), intval(Settings::getExactSetting(SCo::SERVER_PORT)));

    $q = $query->Query();
    if (isset($q['players']['sample'])) {
        foreach ($q['players']['sample'] as $data) {
            $esm = new PlayerESM($data['name']);
            $esm->renderCard();
        }
    } else {
?>
        <div class="col text-center">
            <span style="color: yellow; font-size: 26px">No players online</span>
        </div>
<?php
    }
} catch (MinecraftPingException $e) {
    echo $e->getMessage();
} finally {
    if ($query) {
        $query->Close();
    }
}
