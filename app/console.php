<?php

require_once __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__ . '/config/config.php';

$forecast_service = new \App\Services\ForecastService(new \App\Core\MongoProvider($config['mongo']));

if (isset($argv[1]) && $argv[1] == 'cities-update') {
    $forecast_service->updateCities();
} else {
    $forecast_service->insertForecasts();
}