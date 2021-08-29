<?php

session_start();

use Utils\Toast\ToastHandler as Toast;

require_once './../utils/toast.util.php';
$toast = unserialize($_SESSION['toast']);
$toast->addMessage("Neoprávnený prístup", Toast::SEVERITY_ERROR);
$_SESSION['toast'] = serialize($toast);

header("Location: ./../");
