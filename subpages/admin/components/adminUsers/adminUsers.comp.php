<?php

use Utils\Toast\ToastHandler as Toast;
use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;
use Models\User\UserQueries;

require_once './models/perms.model.php';

require_once './utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_USERS_ACCESS)) {
    $toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
    $_SESSION['toast'] = serialize($toast);

    echo ("<script>location.href = './';</script>");
    exit();
}

?>

<link rel="stylesheet" href="./subpages/admin/components/adminUsers/adminUsers.comp.css">

<div class="container-fluid" style="margin-top: 60px">
    <div class="row justify-content-center">
        <div class="col-11">
            <table class="table table-dark table-striped table-sm">
                <thead>
                    <tr class="text-center align-middle">
                        <th>Username</th>
                        <th style="width: 25%">Rank</th>
                        <th style="width: 25%">Operácie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require './includes/dbh.inc.php';
                    $sql = "SELECT users.id AS id, users.username AS username, r.name AS rankname, r.color AS color
                            FROM users
                            INNER JOIN ranks r ON users.rankId = r.id";
                    $result = $conn->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                            <form action="./includes/admin.userchangerow.inc.php" method="post">
                                <tr class="text-center align-middle text-outline" style="font-size: 16px">
                                    <input type="hidden" name="user-selected" value="' . $row['id'] . '"></input>
                                    <td>' . $row['username'] . '</td>
                                    <td class="text-no-outline">
                                        
                            ';
                            $noPermsForThisUserEdit = UserQueries::isUserLowerRankThanTargetUser($_SESSION['id'], $row['id']);
                            if ($noPermsForThisUserEdit) {
                                echo '<span class="text-outline" style="color: ' . $row['color'] . '"><strong>' . $row['rankname'] . '</strong></span>';
                            } else {
                                echo '<select class="form-select" name="rank-selected">';
                                $sql = "SELECT * FROM ranks ORDER BY rank ASC";
                                $resultRanks = $conn->query($sql);
                                while ($rowRanks = mysqli_fetch_assoc($resultRanks)) {
                                    if (UserQueries::isUserLowerRankThanTargetRank($_SESSION['id'], $rowRanks['id'])) continue;
                                    echo '<option value="' . $rowRanks['id'] . '" ' . ($row['rankname'] == $rowRanks['name'] ? "selected" : "") .  '>' . $rowRanks['name'] . '</option>';
                                }
                                echo '
                                    </select>
                                </td>
                                ';
                            }
                            echo '
                            <td>
                                <div class="row justify-content-center">
                                    <div class="col-md-12 col-xl-6">
                                        <button class="btn btn-primary" name="form-submit" type="submit" ' . ($noPermsForThisUserEdit ? 'disabled style="background-color: grey"' : '') . '>Uložiť</button>
                                    </div>
                                </div>
                            </td>';
                            echo '
                            </tr>
                            </form>
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