<?php

use Repositories\RedisClientsSourcesStoreRepository;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../vendor/autoload.php';

$config = require __DIR__ . '/../config.php';
require __DIR__ . '/../initApp.php';

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
	)
);
$app->run();
