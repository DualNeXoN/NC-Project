<link rel="stylesheet" href="./subpages/players/components/playerprofile/playerprofile.comp.css">

<div class="container-fluid container-profile">

    <div class="row row-profile-avatar">
        <div class="col text-center align-self-center">
            <img class="img-avatar" src="http://cravatar.eu/avatar/<?= $_GET['player'] ?>/120.png" />
        </div>
    </div>

    <div class="row row-profile-name">
        <div class="col text-center align-self-center">
            <span class="profile-name text-outline"><?= $_GET['player'] ?></span>
        </div>
    </div>

    <?php

    use Utils\Toast\ToastHandler;
    use Models\User\User as User;
    use Models\User\UserQueries as Queries;

    require_once './utils/toast.util.php';

    require './includes/dbh.inc.php';

    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_GET['player']);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $onlineWeb = Queries::isOnlineOnWeb($row['id']);
        $userClassTmp = new User($row['id'], $row['username']);
        echo '
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline" style="color: ' . $userClassTmp->getRankColor() . '">' . $userClassTmp->getRankName() . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Registrácia: ' . $row['registrationDate'] . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Status na serveri: </span>
                <span class="text-outline" style="color: ' . ($row['onlineServer'] ? "lightgreen" : "red") . '">' . ($row['onlineServer'] ? "Online" : "Offline") . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Status na webe: </span>
                <span class="text-outline" style="color: ' . ($onlineWeb ? "lightgreen" : "red") . '">' . ($onlineWeb ? "Online" : "Offline") . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Posledné prihlásenie na webe: ' . ($row['lastLoginWeb'] != null ? $row['lastLoginWeb'] : "Nikdy") . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Posledné prihlásenie na serveri: ' . ($row['lastLoginServer'] != null ? $row['lastLoginServer'] : "Nikdy") . '</span>
            </div>
        </div>
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline">Playtime: ' . $row['playtime'] . '</span>
            </div>
        </div>
        ';
        if ($row['isBanned']) echo '
        <div class="row">
            <div class="col text-center align-self-center">
                <span class="text-outline" style="color: red">Zabanovaný</span>
            </div>
        </div>
        ';
    } else {
        $toast = unserialize($_SESSION['toast']);
        $toast->addMessage("Hráč sa nenašiel", ToastHandler::SEVERITY_ERROR);
        $_SESSION['toast'] = serialize($toast);
        echo '<script>location.href="./?subpage=players";</script>';
    }

    ?>

    <div class="row justify-content-center" style="margin-top: 20px">
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-8 text-center align-self-center">
            <button class="btn btn-primary" style="width: 100%">Pošli súkromnú správu</button>
        </div>
    </div>

</div>