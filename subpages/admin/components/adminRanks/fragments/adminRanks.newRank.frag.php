<?php

use Models\Perms\PermsConstants;
use Models\Perms\PermsHandler;

require_once './models/perms.model.php';

if (!PermsHandler::hasPerms(PermsConstants::ADMINPANEL_RANKS_ADD)) {
    return;
}

?>

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
                    <input class="form-control" type="text" name="new-rank-name" placeholder="Názov ranku" required></input>
                    <input class="form-control" type="number" name="new-rank-value" placeholder="Hodnota ranku" required></input>
                    <input class="form-control" id="new-rank-color" name="new-rank-color" value="#ffffff" readonly>
                    <canvas id="new-rank-color-picker"></canvas>
                    <button class="btn btn-primary" name="form-submit" type="submit">Pridať</button>
                </form>
                <script>
                    new KellyColorPicker({
                        place: "new-rank-color-picker",
                        input: "new-rank-color"
                    });
                </script>
            </div>
        </div>
    </div>
</div>