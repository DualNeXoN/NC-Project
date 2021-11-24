const listElement = "#esm-list";

function fetchPlayerlistData() {
    console.log("fetched");
    $(listElement).load('./subpages/admin/components/adminServer/fragments/adminESM/adminESM.list.fetcher.php');
}

$('document').ready(function () {
    fetchPlayerlistData();
    setInterval(fetchPlayerlistData, 15 * 1000);
});