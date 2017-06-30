<?php

use Controllers\InformClientController;
use Controllers\RegisterClientController;
use Controllers\UnregisterClientController;
use Controllers\Validators\InformClientMessageJsonValidator;
use Controllers\Validators\RegisterClientMessageJsonValidator;
use Controllers\Validators\UnregisterClientMessageJsonValidator;
use Domain\InformClientService;
use Repositories\RedisClientsSourcesStoreRepository;

error_reporting(E_ALL);
ini_set('display_errors', 1);

$config = require __DIR__ . '/../config.php';

require __DIR__ . '/../../vendor/autoload.php';
$configuration = [
	'settings' => [
		'displayErrorDetails' => true,
	],
];
$container = new \Slim\Container($configuration);
$app = new Slim\App($container);

$redis = new Redis($config['redis']['host'], $config['redis']['port']);

$app->get('/register-client', function ($request, $response, $args) use ($redis) {
	$registerClientController = new RegisterClientController(
		new RedisClientsSourcesStoreRepository($redis),
		new RegisterClientMessageJsonValidator()
	);
	$registerClientController->run($request, $response);
});

$app->get('/unregister-client', function ($request, $response, $args) use ($redis) {
	$registerClientController = new UnregisterClientController(
		new RedisClientsSourcesStoreRepository($redis),
		new UnregisterClientMessageJsonValidator()
	);
	$registerClientController->run($request, $response);
});

$app->get('/inform-clients', function ($request, $response, $args) use ($redis) {
	$registerClientController = new InformClientController(
		new InformClientMessageJsonValidator(),
		new InformClientService(
			new RedisClientsSourcesStoreRepository($redis),
			200
		)
	);
	$registerClientController->run($request, $response);
});

$app->post('/', function ($request, $response, $args) {

});

$app->run();
