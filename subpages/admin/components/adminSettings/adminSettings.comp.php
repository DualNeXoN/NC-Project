<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_SETTINGS_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminSettings/adminSettings.comp.css">

<div class="container-fluid" style="margin-top: 60px">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>Kľúč</th>
                            <th>Hodnota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';
                        $sql = "SELECT * FROM settings";
                        $result = $conn->query($sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <form action="./includes/admin.settings.change.inc.php" method="post">
                                    <input type="hidden" name="form-submit" />
                                    <tr class="text-center align-middle text-outline" style="font-size: 16px">
                                        <th scope="row">
                                            <?= $row['keyName'] ?>
                                            <input type="hidden" name="setting-selected" value="<?= $row['keyName'] ?>"></input>
                                        </th>
                                        <td class="text-no-outline">
                                            <input class="form-control" type="input" name="setting-new-value" value="<?= $row['value'] ?>" onchange="this.submit()"></input>
                                        </td>
                                    </tr>
                                </form>
                        <?php
                            }
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>