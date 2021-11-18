function reactToAnnouncement(announcementId, userId, reactionsId = 1) {

    let isLiked = $("#like-button-" + announcementId).hasClass("btn-success");

    if (isLiked) {
        $.ajax({
            url: './includes/announcements.reaction.inc.php',
            type: 'POST',
            data: {
                "submit-like-undo": "1",
                "announcementId": announcementId,
                "userId": userId,
                "reactionsId": reactionsId
            },
            success: function () {
                updateReactionButton(announcementId, false);
                changeReactionCountValueBy(announcementId, -1);
            }
        });
    } else {
        $.ajax({
            url: './includes/announcements.reaction.inc.php',
            type: 'POST',
            data: {
                "submit-like": "1",
                "announcementId": announcementId,
                "userId": userId,
                "reactionsId": reactionsId
            },
            success: function () {
                updateReactionButton(announcementId, true);
                changeReactionCountValueBy(announcementId, 1);
            }
        });
    }
}

function updateReactionButton(announcementId, liked) {

    let btn = $("#like-button-" + announcementId);
    let btnIcon = $("#like-button-icon-" + announcementId);
    let btnVal = $("#like-button-value-" + announcementId);

    if (liked) {
        btn.removeClass("btn-primary");
        btn.addClass("btn-success");
        btnIcon.removeClass("far");
        btnIcon.addClass("fas");
        btnVal.text("Liked");
    } else {
        btn.removeClass("btn-success");
        btn.addClass("btn-primary");
        btnIcon.removeClass("fas");
        btnIcon.addClass("far");
        btnVal.text("Like");
    }
}

function changeReactionCountValueBy(announcementId, value) {
    let reactionVal = $("#reaction-count-" + announcementId);
    reactionVal.text(parseInt(reactionVal.text()) + value);
}

function flipEditAnnouncement(announcementId) {
    let btn = $("#button-edit-" + announcementId);
    let titleSection = $("#announcement-title-" + announcementId);
    let messageSection = $("#announcement-message-" + announcementId);
    let titleSectionInput = $("#announcement-title-edit-" + announcementId);
    let messageSectionInput = $("#announcement-message-edit-" + announcementId);

    if (titleSection.is(":visible")) {
        btn.removeClass("btn-primary");
        btn.addClass("btn-success");
        btn.text("Uložiť");
        titleSection.attr("hidden", true);
        messageSection.attr("hidden", true);
        titleSectionInput.attr("hidden", false);
        messageSectionInput.attr("hidden", false);
    } else {
        btn.removeClass("btn-success");
        btn.addClass("btn-primary");
        btn.text("Upraviť");
        titleSection.attr("hidden", false);
        messageSection.attr("hidden", false);
        titleSectionInput.attr("hidden", true);
        messageSectionInput.attr("hidden", true);

        $.ajax({
            url: './includes/announcements.edit.inc.php',
            type: 'POST',
            data: {
                "form-submit": "1",
                "announcementId": announcementId,
                "title": titleSectionInput.val(),
                "message": messageSectionInput.val()
            },
            success: function () {
                titleSection.text(titleSectionInput.val());
                messageSection.text(messageSectionInput.val());
            }
        });
    }
}