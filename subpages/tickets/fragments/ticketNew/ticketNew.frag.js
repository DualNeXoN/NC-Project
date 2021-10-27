$(document).ready(function () {

    let elementId = "#btn-collapse-new-ticket";
    let closedValue = "Napísať nový ticket";
    let openedValue = "Zatvoriť";

    $(elementId).attr('value', closedValue);

    $(elementId).click(function () {
        if ($(this).attr('value') == closedValue) {
            $(this).attr('value', openedValue);
        } else {
            $(this).attr('value', closedValue);
        }
    });
});