<?php

use Models\User\UserQueries as Queries;

require_once './models/user.model.php';

if (!isset($_SESSION['user'])) return;

Queries::updateLastActivityTime(unserialize($_SESSION['user'])->getId());
