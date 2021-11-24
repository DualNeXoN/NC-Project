const listElement = "#esm-list";

function fetchPlayerlistData() {
    $(listElement).load('./subpages/admin/components/adminServer/fragments/adminESM/adminESM.list.fetcher.php');
}

$('document').ready(function () {
    fetchPlayerlistData();
    setInterval(fetchPlayerlistData, 15 * 1000);
});