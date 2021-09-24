function fetchPlayerlistData() {
    $('#playerlist-data').load('./subpages/players/components/playerlist/fragments/table/playerlist.fetcher.php');
}

$('document').ready(function () {
    fetchPlayerlistData();
    setInterval(fetchPlayerlistData, 10000);
});