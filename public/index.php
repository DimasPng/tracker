<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;

$app = new Application();

$providers = require __DIR__ . '/../config/providers.php';

foreach ($providers as $providerClass) {
    $app->register($providerClass);
}

$app->run();
