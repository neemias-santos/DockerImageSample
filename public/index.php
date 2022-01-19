<?php
require_once __DIR__ . '/../vendor/autoload.php';

use dockerPHP\Session;

$app = new Session();

echo $app->handleSessionData([]);