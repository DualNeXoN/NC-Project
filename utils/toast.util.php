<?php

namespace Utils\Toast {

    class ToastHandler {

        public const SEVERITY_ERROR = "danger";
        public const SEVERITY_SUCCESS = "success";
        public const SEVERITY_WARNING = "warning";
        public const SEVERITY_INFO = "info";

        private array $messages = array();

        function __construct() {
            $this->flushMessages();
        }

        public function flushMessages() {
            unset($this->messages);
        }

        public function addMessage(String $message, String $severity) {
            $toast = new ToastMessage($message, $severity);
            array_push($this->messages, $toast);
        }

        public function process() {
            echo '<div class="toast-container position-fixed end-0 p-2" style="z-index: 10000; top: 80px">';
            foreach ($this->messages as $message) {
                echo '
                <div class="toast hide align-items-center text-white bg-' . $message->getSeverity() . ' border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ' . $message->getMessage() . '
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
                ';
            }
            echo '</div>';
            $this->flushMessages();
        }

        public function getMessageCount() {
            return count($this->messages);
        }
    }

    class ToastMessage {

        private String $message;
        private String $severity;

        function __construct(String $message, String $severity = ToastHandler::SEVERITY_INFO) {
            $this->message = $message;
            $this->severity = $severity;
        }

        public function getMessage() {
            return $this->message;
        }

        public function getSeverity() {
            return $this->severity;
        }
    }
}

namespace {
    if (!isset($_SESSION['toast'])) $_SESSION['toast'] = serialize(new Utils\Toast\ToastHandler());
}
