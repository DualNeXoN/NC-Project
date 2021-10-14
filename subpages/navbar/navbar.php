<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="./">
            <img src="images/logo.png" width="auto" height="45" class="d-inline-block align-top" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="./">Hlavná stránka</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./?subpage=players">Hráči</a>
                </li>
                <?php require_once 'fragments/navbarTickets/navbarTickets.frag.php' ?>
                <?php require_once 'fragments/navbarAdmin/navbarAdmin.frag.php' ?>
            </ul>
            <?php
            if (isset($_SESSION['user'])) {
                require 'fragments/navbarAccount/navbarAccount.frag.php';
            } else if ((isset($_GET['subpage']) && $_GET['subpage'] != "login") || !isset($_GET['subpage'])) {
                require 'fragments/navbarLogin/navbarLogin.frag.php';
            }
            ?>
        </div>
    </div>
</nav>