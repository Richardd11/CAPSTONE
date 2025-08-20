<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Controllers\Auth\AuthController;

$authController = new AuthController();
$authController->logout();