<?php

namespace Models\PlayerESM {

    class PlayerESM {

        private String $name;

        function __construct(String $name) {
            $this->name = $name;
        }

        function renderCard() {
?>
            <div class="col-11 col-sm-11 col-md-5 col-xl-3 text-center" style="background-color: #4a4545; padding: 10px; margin: 8px; border-radius: 8px; border: 1px solid grey">
                <div class="row justify-content-center">
                    <div class="col-12"><img src="https://cravatar.eu/avatar/<?= $this->name ?>/75.png"></div>
                    <div class="col-12"><?= $this->name ?></div>
                    <div class="col-6"><button class="btn btn-warning" style="width: 100%">Kick</button></div>
                    <div class="col-6"><button class="btn btn-danger" style="width: 100%">Ban</button></div>
                </div>
            </div>
<?php
        }
    }
}
