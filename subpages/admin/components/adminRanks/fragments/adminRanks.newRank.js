$(document).ready(function () {

    let closedValue = "Pridať rank";
    let openedValue = "Zatvoriť";

    $("#btn-collapse-new-rank").attr('value', closedValue);

    $("#btn-collapse-new-rank").click(function () {
        if ($(this).attr('value') == closedValue) {
            $(this).attr('value', openedValue);
        } else {
            $(this).attr('value', closedValue);
        }
    });
});