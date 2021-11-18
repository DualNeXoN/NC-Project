<link rel="stylesheet" href="./subpages/announcements/fragments/announcementsList/announcementsList.fetcher.css" />
<script src="./subpages/announcements/fragments/announcementsList/announcementsList.fetcher.js"></script>

<?php

use Models\Announcement\Announcement;
use Models\Announcement\AnnouncementQueries;

require './models/announcement.model.php';

$announcements = AnnouncementQueries::getAllAnnouncements();

if (mysqli_num_rows($announcements) == 0) {
    exit();
}

while ($announcementRow = mysqli_fetch_assoc($announcements)) {
    $announcement = new Announcement($announcementRow['id']);
    $editOperations = $announcement->canLoggedUserEditAnnouncement();
    $wasAnnouncementEdited = ($announcement->getEditTimestamp() != null);
    $userReacted = $announcement->hasLoggedUserReacted();
?>

    <div id="announcement-<?= $announcement->getId() ?>" class="row announcement-container">
        <div class="col-xxl-2 col-lg-3 col-md-4 col-12 announcement-details-container">
            <div class="row" style="max-height: 90px">
                <div class="col">
                    <a href="<?= $announcement->getUser()->getUserLink() ?>">
                        <img src="<?= $announcement->getUser()->getAvatarLink() ?>" class="rounded" style="width: 100%; max-width: 80px" />
                    </a>
                </div>
            </div>
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <span class="text-outline" style="font-size: 20px; color: <?= $announcement->getUser()->getRankColor() ?>"><?= $announcement->getUser()->getRankName() ?></span>
                </div>
                <div class="col-12">
                    <span class="text-outline"><strong><?= $announcement->getUser()->getUsername() ?></strong></span>
                    <hr class="hr-user-side">
                </div>
                <div class="col-12">
                    <span>Dátum pridania</span>
                </div>
                <div class="col-12">
                    <span><?= $announcement->getCreateDay() ?></span>
                </div>
                <div class="col-12">
                    <span><?= $announcement->getCreateTime() ?></span>
                    <?= ($wasAnnouncementEdited ? '<hr class="hr-user-side">' : null) ?>
                </div>
                <?php
                if ($wasAnnouncementEdited) {
                ?>
                    <div class="col-12">
                        Dátum úpravy
                    </div>
                    <div class="col-12">
                        <span><?= $announcement->getEditDay() ?></span>
                    </div>
                    <div class="col-12">
                        <span><?= $announcement->getEditTime() ?></span>
                        <?= ($editOperations ? '<hr class="hr-user-side">' : null) ?>
                    </div>
                <?php
                }
                if ($editOperations) {
                ?>
                    <div class="col-md-11 col-6 edit-operation">
                        <button id="button-edit-<?= $announcement->getId() ?>" class="btn btn-primary full-width" onclick="flipEditAnnouncement(<?= $announcement->getId() ?>)">Upraviť</button>
                    </div>
                    <div class="col-md-11 col-6 edit-operation">
                        <button disabled class="btn btn-primary full-width">Skryť</button>
                    </div>
                    <div class="col-md-11 col-6 edit-operation">
                        <form action="./includes/announcements.delete.inc.php" method="post">
                            <input type="hidden" name="announcementId" value="<?= $announcement->getId() ?>" />
                            <button class="btn btn-danger full-width" name="form-submit" type="submit">Zmazať</button>
                        </form>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <div class="col">
            <div class="row justify-content-center">
                <div class="col text-outline" style="font-size: 26px">
                    <span id="announcement-title-<?= $announcement->getId() ?>"><?= $announcement->getTitle() ?></span>
                    <?php
                    if ($editOperations) {
                    ?>
                        <textarea hidden id="announcement-title-edit-<?= $announcement->getId() ?>" style="width: 100%"><?= $announcement->getTitle() ?></textarea>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col" style="text-justify: inner-word; text-align: justify">
                    <span id="announcement-message-<?= $announcement->getId() ?>"><?= $announcement->getMessage() ?></span>
                    <?php
                    if ($editOperations) {
                    ?>
                        <textarea hidden id="announcement-message-edit-<?= $announcement->getId() ?>" style="width: 100%; height: 200px"><?= $announcement->getMessage() ?></textarea>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <hr>
            <div class="d-flex flex-row-reverse">
                <div class="d-inline-flex p-2">
                    <?php
                    if (isset($_SESSION['id'])) {
                    ?>
                        <div class="p-2">
                            <button id="like-button-<?= $announcement->getId() ?>" class="btn btn-<?= $userReacted ? "success" : "primary" ?>" onclick="reactToAnnouncement(<?= $announcement->getId() . ',' . $_SESSION['id'] ?>)">
                                <span id="like-button-value-<?= $announcement->getId() ?>"><?= $userReacted ? "Liked" : "Like" ?></span>
                                <i id="like-button-icon-<?= $announcement->getId() ?>" class="<?= $userReacted ? "fas" : "far" ?> fa-thumbs-up"></i>
                            </button>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="d-inline-flex p-2">
                    <div class="p-2">
                        <span id="reaction-count-<?= $announcement->getId() ?>"><?= $announcement->getReactionCount() ?></span>
                        <i class="far fa-thumbs-up"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
}
