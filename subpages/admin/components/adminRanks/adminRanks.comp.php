<link rel="stylesheet" href="./subpages/admin/components/adminRanks/adminRanks.comp.css">

<div class="container-fluid">
    <div class="row justify-content-center" style="margin-top: 60px">
        <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 col-xxl-2 text-center">
            <button class="btn btn-primary btn-rank-add" data-bs-toggle="collapse" data-bs-target="#collapseNewRank" aria-expanded="false" aria-controls="collapseNewRank">Pridať rank</button>
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top: 8px">
        <div class="col-10 col-sm-8 col-md-6 text-center">
            <div class="collapse" id="collapseNewRank">
                <div class="card card-body bg-dark" style="color: white">
                    <form class="form-new-rank" action="./includes/admin.rankadd.inc.php" method="post">
                        <input class="form-control" type="text" name="rank-name" placeholder="Názov ranku" required></input>
                        <input class="form-control" type="number" name="rank-value" placeholder="Hodnota ranku" required></input>
                        <button class="btn btn-primary" name="form-submit" type="submit">Pridať</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top: 60px">
        <div class="col-11">
            <table class="table table-dark table-striped table-sm">
                <thead>
                    <tr class="text-center align-middle">
                        <th>Rank</th>
                        <th style="width: 25%">Hodnota</th>
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
                                    <th scope="row">' . $row['name'] . '<input type="hidden" name="rank-selected" value="' . $row['id'] . '"></input></th>
                                    <td>
                                        <input class="form-control" type="number" min="0" max="100000" name="rank-value" value="' . $row['rank'] . '"></input>
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
                        }
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>