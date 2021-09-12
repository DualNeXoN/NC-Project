<div class="container-fluid" style="padding: 0 0; margin: 50px 0; color: white;">

    <div class="container-fluid">

        <?php
        if (!isset($_GET['player'])) {
            require './subpages/players/components/playerlist/playerlist.comp.php';
        } else {
            require './subpages/players/components/playerprofile/playerprofile.comp.php';
        }
        ?>

    </div>

</div>