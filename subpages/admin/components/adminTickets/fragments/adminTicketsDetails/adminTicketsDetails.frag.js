const messagesElement = "#messages";
const messagesContainerElement = "#messages-container";
const ticketMessageInputElementId = "#ticket-message-input";
const ticketMessageSendButtonId = "#ticket-message-send-button";

function sendTicketMessage(ticketId) {

    let ticketMessageInputElement = $(ticketMessageInputElementId);

    setTimeout(function () {
        ticketMessageInputElement.val("");
    }, 10);

    $.ajax({
        url: 'includes/ticket.sendMessage.inc.php',
        type: 'POST',
        data: {
            ticketId: ticketId,
            message: ticketMessageInputElement.val()
        },
        success: function () {
            fetchPlayerlistData();
            scrollToLatestMessage(1000);
        }
    });
}

$(ticketMessageInputElementId).on('keypress', function (event) {
    if (event.which == 13) {
        $(ticketMessageSendButtonId).trigger("click");
    }
});

function scrollToLatestMessage(delay) {
    setTimeout(function () {
        $(messagesElement).scrollTop($(messagesElement).get(0).scrollHeight);
    }, delay);
}

function fetchPlayerlistData() {
    $(messagesContainerElement).load('./subpages/admin/components/adminTickets/fragments/adminTicketsDetails/adminTicketsDetails.messages.fetcher.php');
}

$('document').ready(function () {
    fetchPlayerlistData();
    setInterval(fetchPlayerlistData, 15 * 1000);
});