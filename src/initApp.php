<?php

use Controllers\InformClientController;
use Controllers\RegisterClientController;
use Controllers\UnregisterClientController;
use Controllers\Validators\InformClientMessageJsonValidator;
use Controllers\Validators\RegisterClientMessageJsonValidator;
use Controllers\Validators\UnregisterClientMessageJsonValidator;
use Domain\Adapters\ClientsSourcesStoreRepositoryInterface;
use Domain\Adapters\HttpRequestToWsServerSenderInterface;
use Domain\ClientIdDecoder;
use Domain\DecodedClientsIdsCollectionByUniqueUriGrouper;
use Domain\InformClientService;
use Domain\MessageSender;
use Slim\Http\Response;

function initApp(
	\Slim\Container $container,
	ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository,
	HttpRequestToWsServerSenderInterface $httpRequestToWsServerSender,
	int $paginationLimitInInformingClients,
	array $config
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

	$informClientController = new InformClientController(
		new InformClientMessageJsonValidator(),
		new InformClientService(
			$clientsSourcesStoreRepository,
			new MessageSender(
				new ClientIdDecoder(),
				new DecodedClientsIdsCollectionByUniqueUriGrouper(),
				$httpRequestToWsServerSender
			),
			$paginationLimitInInformingClients
		)
	);
	$app->post('/inform-clients', function ($request, $response) use ($informClientController) {
		$informClientController->run($request, $response);
	});

	$app->get('/', function ($request, Response $response, $args) use ($config) {
		$response->write(json_encode(
			[
				'type' => 'source-listener',
				'config' => $config,
			]
		));
	});

	return $app;
}
