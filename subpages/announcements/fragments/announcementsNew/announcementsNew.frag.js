$(document).ready(function () {

    let closedValue = "Pridať oznam";
    let openedValue = "Zatvoriť";

    $("#btn-collapse-new-announcement").attr('value', closedValue);

    $("#btn-collapse-new-announcement").click(function () {
        if ($(this).attr('value') == closedValue) {
            $(this).attr('value', openedValue);
        } else {
            $(this).attr('value', closedValue);
        }
    });
});