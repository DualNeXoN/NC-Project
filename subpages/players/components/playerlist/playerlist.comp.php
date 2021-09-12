<link rel="stylesheet" href="./subpages/players/components/playerlist/playerlist.comp.css">

<div class="row" style="margin-bottom: 60px">
    <div class="col text-center align-self-center">
        <h2>Zoznam hráčov</h2>
    </div>
</div>

<div class="container-fluid" style="margin-top: 60px">
    <div class="row justify-content-center">
        <div class="col-xxl-5 col-xl-6 col-lg-7 col-md-8 col">
            <table class="table table-hover">
                <thead>
                    <tr class="text-center align-middle">
                        <th>List</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    use Models\User\UserQueries;

                    require './includes/dbh.inc.php';
                    $sql = "SELECT users.id AS userId, users.username AS username, users.rankValue AS rankValue, r.name AS rankname, r.color AS color
                            FROM users
                            INNER JOIN ranks r ON users.rankId = r.id
                            ORDER BY rankValue, username ASC";
                    $result = $conn->query($sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '
                                <tr class="clickable-row align-middle text-outline" onclick="location.href=\'./?subpage=players&player=' . $row['username'] . '\'" style="font-size: 16px">
                                    <th scope="row">
                                        <span><img src="http://cravatar.eu/avatar/' . $row['username'] . '/40.png" style="border: 5px outset ' . (UserQueries::isOnlineOnWeb($row['userId']) ? "lightgreen" : "red") . '"/></span>
                                        <span style="color: ' . $row['color'] . '">' . $row['rankname'] . '</span>
                                        <span>&#9679;</span>
                                        <span>' . $row['username'] . '</span></td>
                                    </th>
                                </tr>
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