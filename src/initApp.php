<?php

use Controllers\RegisterClientController;
use Controllers\UnregisterClientController;
use Controllers\Validators\RegisterClientMessageJsonValidator;
use Controllers\Validators\UnregisterClientMessageJsonValidator;
use Domain\Adapters\ClientsSourcesStoreRepositoryInterface;

function initApp(
	\Slim\Container $container,
	ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository
)
{
	$app = new Slim\App($container);

	$registerClientController = new RegisterClientController(
		$clientsSourcesStoreRepository,
		new RegisterClientMessageJsonValidator()
	);
	$app->post('/register-client', function ($request, $response) use ($registerClientController) {
		$registerClientController->run($request, $response);
	});

	$unregisterClientController = new UnregisterClientController(
		$clientsSourcesStoreRepository,
		new UnregisterClientMessageJsonValidator()
	);
	$app->post('/unregister-client', function ($request, $response) use ($unregisterClientController) {
		$unregisterClientController->run($request, $response);
	});

//$app->get('/inform-clients', function ($request, $response, $args) use ($redis, $config) {
//	$registerClientController = new InformClientController(
//		new InformClientMessageJsonValidator(),
//		new InformClientService(
//			new RedisClientsSourcesStoreRepository($redis),
//			$config['pagination_limit']
//		)
//	);
//	$registerClientController->run($request, $response);
//});

//	$app->get('/', function ($request, $response, $args) use ($container) {
//
//		$registerClientController = new RegisterClientController(
//			$container->get('clientSourceStore'),
//			new RegisterClientMessageJsonValidator()
//		);
//		$registerClientController->run($request, $response);
//
////	$response->write("source listener" . $container->get('abcasf'));
//	});

	return $app;
}
