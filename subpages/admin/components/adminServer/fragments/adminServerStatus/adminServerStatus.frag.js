const statusElement = "#admin-server-status";

function fetchStatus() {
    $(statusElement).load('./subpages/admin/components/adminServer/fragments/adminServerStatus/adminServerStatus.status.fetcher.php');
}

$('document').ready(function () {
    fetchStatus();
    setInterval(fetchStatus, 15 * 1000);
});