$("#consoleInput").on('keypress', function (event) {
    if (event.which == 13) {
        let element = $("#consoleInput");
        sendConsoleCommand(element.val());
        element.val("");
    }
});

function sendConsoleCommand(command) {
    $.ajax({
        url: './includes/admin.console.sendCommand.inc.php',
        type: 'POST',
        data: {
            "cmd": command
        },
        success: function (response) {
            updateConsoleOutput(response);
        }
    });
}

function updateConsoleOutput(output) {
    let element = $("#consoleOutput");
    element.val(element.val() + output + "\n");
}