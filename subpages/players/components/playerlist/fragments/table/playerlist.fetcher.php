<?php

use Models\User\UserQueries;

require $_SERVER['DOCUMENT_ROOT'] . '/includes/dbh.inc.php';
require $_SERVER['DOCUMENT_ROOT'] . '/models/user.model.php';
$sql = "SELECT users.id AS userId, users.username AS username, users.rankValue AS rankValue, r.name AS rankname, r.color AS color
        FROM users
        INNER JOIN ranks r ON users.rankId = r.id
        ORDER BY rankValue, username ASC";
$result = $conn->query($sql);
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $onlineOnWeb = UserQueries::isOnlineOnWeb($row['userId']);
        $onlineOnServer = UserQueries::isOnlineOnServer($row['userId']);
        $onlineOnBoth = $onlineOnWeb && $onlineOnServer;
        $onlineInGeneral = $onlineOnWeb || $onlineOnServer;
?>
        <tr class="clickable-row align-middle text-outline" onclick="location.href='./?subpage=players&player=<?= $row['username'] ?>'" style="font-size: 16px">
            <th scope="row">
                <span><img src="http://cravatar.eu/avatar/<?= $row['username'] ?>/40.png" onerror="this.src=\'./../../../images/avatar_default.png\'" style="border: 5px <?= ($onlineInGeneral && $onlineOnBoth ? "solid" : "outset") ?> <?= ($onlineInGeneral ? "lightgreen" : "red") ?>" /></span>
                <span style="color: <?= $row['color'] ?>"><?= $row['rankname'] ?></span>
                <span>&#9679;</span>
                <span><?= $row['username'] ?></span></td>
            </th>
        </tr>
<?php
    }
}
$conn->close();
