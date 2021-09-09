<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_RANKS_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminRanks/adminRanks.comp.css">
<script src="./js/colorpicker.js"></script>

<div class="container-fluid">
    <?php include './subpages/admin/components/adminRanks/fragments/adminRanks.newRank.frag.php' ?>
    <div class="row justify-content-center" style="margin-top: 60px">
        <div class="col-11">
            <table class="table table-dark table-striped table-sm">
                <thead>
                    <tr class="text-center align-middle">
                        <th>Rank</th>
                        <th style="width: 25%">Hodnota</th>
                        <th style="width: 15%">Farba</th>
                        <th style="width: 30%">Operácie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require './includes/dbh.inc.php';
                    $sql = "SELECT * FROM ranks ORDER BY rank ASC";
                    $result = $conn->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                            <form action="./includes/admin.rankchangerow.inc.php" method="post">
                                <tr class="text-center align-middle text-outline" style="font-size: 16px">
                                    <th scope="row">
                                        <input class="form-control" name="rank-name" type="text" value="' . $row['name'] . '" maxlength="32" required></input>
                                        <input type="hidden" name="rank-selected" value="' . $row['id'] . '"></input>
                                    </th>
                                    <td>
                                        <input class="form-control" type="number" min="0" max="100000" name="rank-value" value="' . $row['rank'] . '"></input>
                                    </td>
                                    <td>
                                        <input class="form-control" id="color-id-' . $row['id'] . '" value="' . $row['color'] . '" name="input-color" readonly>
                                        <canvas id="picker-id-' . $row['id'] . '" style="display:none"></canvas>
                                    </td>
                                    <td>
                                        <div class="row justify-content-center">
                                            <div class="col-md-12 col-xl-6">
                                                <button class="btn btn-primary" name="form-submit" type="submit">Uložiť</button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </form>
                            ';

                            echo '
                            <script>
                                $("#color-id-' . $row['id'] . '").click(function() {
                                    let picker = $("#picker-id-' . $row['id'] . '");
                                    if (picker.is(":visible")) picker.hide();
                                    else picker.show();
                                });

                                new KellyColorPicker({
                                    place: "picker-id-' . $row['id'] . '",
                                    input: "color-id-' . $row['id'] . '"
                                });
                            </script>
                            ';
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>