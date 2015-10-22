<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../app/config/config.php';

$app = new Silex\Application();

/*
 * Configurations
 */

$app->register(new Silex\Provider\MonologServiceProvider(), [
    'monolog.logfile' => __DIR__ . '/../logs/development.log',
    'monolog.level' => \Monolog\Logger::ERROR
]);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../app/views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

/*
 * Defining services
 */

$app['mongo_provider'] = new \App\Core\MongoProvider($config['mongo'], $app['monolog']);
$app['forecasts'] =  new \App\Services\ForecastService($app['mongo_provider'], $app['monolog']);

/*
 * Error handler
 */

$app->error(function (Exception $e, $code) {
    switch ($code) {
        case 404 :
            return '<h1>404 Not found</h1> <p>Following page cannot be found</p>';
        case 500 :
            return '<h1>500 Server error</h1> <p>Service is temporary unavailable</p>';
        default :
            return '<h1>' . $code . '</h1> <p>Whoops, look like that something went wrong</p>';
    }
});

/*
 * Routing handler
 */

$app->get('/', function () use ($app) {
    $app['forecasts']->getApiForecasts();
    return $app['twig']->render('index.twig', [
        'cities' => json_encode($app['forecasts']->getAllCities())
    ]);
})
    ->bind('homepage');

$app->get('/getForecast/{city}', function ($city) use ($app) {
    return new \Symfony\Component\HttpFoundation\Response($app['forecasts']->getApiForecast($city, true), 200, ['Content-type' => 'application/json']);
})
    ->bind('forecast')
    ->value('city', 'tomsk');

$app->run();