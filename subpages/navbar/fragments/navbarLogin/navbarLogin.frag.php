<link rel="stylesheet" href="./subpages/navbar/fragments/navbarLogin/navbarLogin.frag.css">

<div class="d-flex">

    <span class="navbar-text">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">Prihlásiť sa</button>
    </span>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-body">
                    <div class="container-fluid" style="padding: 0 0; margin: 25px 0">

                        <div class="container-fluid">

                            <div class="row">
                                <div class="col align-self-center">
                                    <div class="row">
                                        <div class="col text-center">
                                            <h4 class="text-outline">
                                                Prihlásenie
                                            </h4>
                                            <form action="includes/login.inc.php" method="post">
                                                <div class="row">
                                                    <div class="col text-start" style="margin-bottom: 10px;">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                            <input class="full-width form-control" type="text" name="username" placeholder="Prihlasovacie meno">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row text-center">
                                                    <div class="col text-start" style="margin-bottom: 10px;">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input class="full-width form-control" type="password" name="pwd" placeholder="Heslo">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top: 25px">
                                                    <div class="col">
                                                        <button class="btn btn-primary full-width" type="submit" name="login-submit">Prihlásiť sa</button>
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

</div>