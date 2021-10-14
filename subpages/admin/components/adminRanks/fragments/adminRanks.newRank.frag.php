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
        <input type="button" id="btn-collapse-new-rank" class="btn btn-primary btn-rank-add" data-bs-toggle="collapse" data-bs-target="#collapseNewRank" aria-expanded="false" aria-controls="collapseNewRank"></input>
    </div>
</div>

<script src="./subpages/admin/components/adminRanks/fragments/adminRanks.newRank.js"></script>

<div class="row justify-content-center" style="margin-top: 8px">
    <div class="col-10 col-sm-8 col-md-6 text-center">
        <div class="collapse" id="collapseNewRank">
            <div class="card card-body bg-dark" style="color: white">
                <form class="form-new-rank" action="./includes/admin.rankadd.inc.php" method="post">
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-12" style="margin-top: 12px">
                            <input class="form-control" type="text" name="new-rank-name" placeholder="Názov ranku" required></input>
                        </div>
                        <div class="col-lg-6 col-12" style="margin-top: 12px">
                            <input class="form-control" type="number" name="new-rank-value" placeholder="Hodnota ranku" required></input>
                        </div>
                        <div class="col-12" style="margin-top: 12px">
                            <canvas id="new-rank-color-picker" style="margin: 0 auto"></canvas>
                        </div>
                        <div class="col-4" style="margin-top: 12px">
                            <input class="form-control" id="new-rank-color" name="new-rank-color" value="#ffffff" style="text-align: center" readonly>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-sm-8 col-12" style="margin-top: 12px">
                            <button class="btn btn-primary full-width" name="form-submit" type="submit">Pridať</button>
                        </div>
                    </div>
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