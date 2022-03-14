<?php

use App\Application;

require_once './vendor/autoload.php';
require_once './config/config.php';

function dd(...$str) {
    echo "<pre>";
    var_dump($str);
    echo "</pre>";
    exit;
}

$app = new Application();
$app->run();

