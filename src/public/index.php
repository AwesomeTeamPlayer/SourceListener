<?php

use Adapters\HttpRequestToWsServerSender;
use Repositories\RedisClientsSourcesStoreRepository;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../initApp.php';
$config = require __DIR__ . '/../config.php';

$container = new \Slim\Container(
	array_merge(
		[
			'settings' => [
				'displayErrorDetails' => true,
			]
		]
	)
);

$app = initApp(
	$container,
	new RedisClientsSourcesStoreRepository(
		$config['redis']['host'],
		$config['redis']['port']
	),
	new HttpRequestToWsServerSender(),
	$config['pagination_limit']
);
$app->run();
