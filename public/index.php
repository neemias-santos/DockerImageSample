<?php
require_once __DIR__ . '/../vendor/autoload.php';

use dockerPHP\CityGame;

$app = new CityGame();

echo $app->gameChecker([]);