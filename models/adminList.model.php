<?php

namespace Models\AdminList {

    use Models\Perms\PermsHandler;

    require_once $_SERVER['DOCUMENT_ROOT'] . '/models/perms.model.php';

    class AdminList {

        const DEFAULT_IMAGE = "icon_unknown.png";

        private String $label;
        private String $redir;
        private String $perm;
        private String $imgSource;

        function __construct(String $label, String $redir, String $perm, $imgSource = AdminList::DEFAULT_IMAGE) {
            $this->label = $label;
            $this->redir = $redir;
            $this->perm = $perm;
            $this->imgSource = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . "/images/adminList/" . $imgSource;
            return $this;
        }

        public function getLabel() {
            return $this->label;
        }

        public function getRedir() {
            return $this->redir;
        }

        public function getPerm() {
            return $this->perm;
        }

        public function getImgSource() {
            return $this->imgSource;
        }

        public function renderWhenHasPerms() {
            if ($this->hasCurrentUserPerm()) $this->render();
        }

        public function hasCurrentUserPerm() {
            return PermsHandler::hasPerms($this->perm);
        }

        public function render() {
?>
            <div class="button-admin col-xl-3 col-lg-4 col-md-6 col-12">
                <a class="admin-link" href="./?subpage=admin&component=<?= $this->redir ?>">
                    <div class="row">
                        <div class="col-12">
                            <img class="admin-button-icon" src="<?= $this->imgSource ?>"></img>
                        </div>
                        <div class="col-12">
                            <span><?= $this->label ?></span>
                        </div>
                    </div>
                </a>
            </div>
<?php
        }
    }

    class AdminListBuilder {

        public static function build(String $label, String $redir, String $perm, String $imgSource = AdminList::DEFAULT_IMAGE) {
            return new AdminList($label, $redir, $perm, $imgSource);
        }
    }
}
