<?php

require_once './models/user.model.php';
$user = unserialize($_SESSION['user']);
?>

<link rel="stylesheet" href="./subpages/navbar/fragments/navbarAccount.frag.css">

<span class="navbar-text nav-rank text-outline"><strong><?php echo $user->getUsername() ?></strong></span>
<span class="navbar-text nav-rank" style="margin: auto 2px"><strong>&#9679;</strong></span>
<span class="navbar-text nav-rank text-outline" style="color: <?php echo $user->getRank()->getColor() ?>"><strong><?php echo $user->getRankName() ?></strong></span>
<button class="btn no-margin" data-bs-toggle="modal" data-bs-target="#accountModal">
    <img class="nav-link" src="http://cravatar.eu/avatar/<?php echo $user->getUsername() ?>/40.png" id="navbarAccount" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false" />
</button>

<div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body">
                <div class="container modal-account-button">
                    <div class="row justify-content-center">
                        <div class="col text-center modal-account-username"><?php echo $user->getUsername() ?></div>
                    </div>
                </div>
                <div class="container modal-account-button">
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePwdModal">Zmeniť heslo</button>
                        </div>
                    </div>
                </div>
                <div class="container modal-account-button">
                    <div class="row justify-content-center">
                        <div class="col text-center">
                            <form action="includes/logout.inc.php" method="post"><button class="btn btn-danger" type="submit" name="logout-submit">Odhlásiť sa</button></form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="changePwdModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-body">
                <div class="container-fluid">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col align-self-center">
                                <div class="row">
                                    <div class="col text-center">
                                        <h4 class="text-outline modal-changepwd-title">
                                            Zmena hesla
                                        </h4>
                                        <form action="./../../../includes/changepwd.inc.php" method="post">
                                            <div class="row text-center">
                                                <div class="col text-start" style="margin-bottom: 10px;">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input class="full-width form-control" required type="password" name="current-pwd" placeholder="Aktuálne heslo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col text-start" style="margin-bottom: 10px;">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input class="full-width form-control" required type="password" name="new-pwd" placeholder="Nové heslo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row text-center">
                                                <div class="col text-start" style="margin-bottom: 10px;">
                                                    <div class="input-group">
                                                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                        <input class="full-width form-control" required type="password" name="new-pwd-repeat" placeholder="Zopakuj nové heslo">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col modal-changepwd-button">
                                                    <button class="btn btn-primary" type="submit" name="changepwd-submit">Zmeniť heslo</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>