<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_PERMS_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminPerms/adminPerms.comp.css">

<div class="container-fluid" style="margin-top: 60px">
    <div class="row justify-content-center">
        <div class="col-11">
            <div class="table-responsive">
                <table class="table table-dark table-striped table-sm">
                    <thead>
                        <tr class="text-center align-middle">
                            <th>Právo</th>
                            <th>Rank</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';
                        $sql = "SELECT
                                perms.keyName AS keyName, perms.rankId AS rankId, r.name AS rankname
                                FROM perms
                                INNER JOIN ranks r ON perms.rankId = r.id";
                        $result = $conn->query($sql);
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                                <form action="./includes/admin.permchangerow.inc.php" method="post">
                                    <input type="hidden" name="form-submit" />
                                    <tr class="text-center align-middle text-outline" style="font-size: 16px">
                                        <th scope="row"><?= $row['keyName'] ?><input type="hidden" name="perm-selected" value="<?= $row['keyName'] ?>"></input></th>
                                        <td class="text-no-outline">
                                            <select class="form-select" name="rank-selected" onchange="this.form.submit()">
                                                <?php
                                                $sql = "SELECT * FROM ranks ORDER BY rank ASC";
                                                $resultRanks = $conn->query($sql);
                                                while ($rowRanks = mysqli_fetch_assoc($resultRanks)) {
                                                ?>
                                                    <option value="<?= $rowRanks['id'] ?>" <?= ($row['rankname'] == $rowRanks['name'] ? "selected" : "") ?>><?= $rowRanks['name'] ?></option>
                                                <?php
                                                }
                                                ?>

                                            </select>
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