<link rel="stylesheet" href="./subpages/announcements/fragments/announcementsNew/announcementsNew.frag.css" />

<div class="row justify-content-center" style="margin-top: 60px">
    <div class="col-10 col-sm-8 col-md-6 col-lg-4 col-xl-3 col-xxl-2 text-center">
        <input id="btn-collapse-new-announcement" type="button" class="btn btn-primary btn-rank-add" data-bs-toggle="collapse" data-bs-target="#collapseNewAnnouncement" aria-expanded="false" aria-controls="collapseNewAnnouncement"></input>
    </div>
</div>

<script src="./subpages/announcements/fragments/announcementsNew/announcementsNew.frag.js"></script>

<div class="row justify-content-center" style="margin-top: 8px">
    <div class="col-10 col-sm-8 col-md-6 text-center">
        <div class="collapse" id="collapseNewAnnouncement">
            <div class="card card-body bg-dark" style="color: white">
                <form action="./includes/announcements.add.inc.php" method="post">
                    <div class="row justify-content-center">
                        <div class="col-12" style="margin-top: 12px">
                            <input class="form-control" type="text" name="new-title" placeholder="Nadpis" required></input>
                        </div>
                        <div class="col-12" style="margin-top: 12px">
                            <textarea class="form-control" type="number" name="new-message" placeholder="Správa" required></textarea>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-lg-6 col-sm-8 col-12" style="margin-top: 12px">
                            <button class="btn btn-primary full-width" name="form-submit" type="submit">Pridať</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>