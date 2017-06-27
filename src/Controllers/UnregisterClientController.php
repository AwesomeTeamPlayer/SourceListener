<?php

namespace Controllers;

use Controllers\Validators\UnregisterClientMessageJsonValidator;
use Domain\ClientsSourcesStoreRepositoryInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UnregisterClientController implements ControllerInterface
{

	/**
	 * @var ClientsSourcesStoreRepositoryInterface
	 */
	private $clientsSourcesStoreRepository;

	/**
	 * @var UnregisterClientMessageJsonValidator
	 */
	private $unregisterClientMessageJsonValidator;

	/**
	 * @param ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository
	 * @param UnregisterClientMessageJsonValidator $unregisterClientMessageJsonValidator
	 */
	public function __construct(
		ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository,
		UnregisterClientMessageJsonValidator $unregisterClientMessageJsonValidator
	)
	{
		$this->clientsSourcesStoreRepository = $clientsSourcesStoreRepository;
		$this->unregisterClientMessageJsonValidator = $unregisterClientMessageJsonValidator;
	}

	public function run(Request $request, Response $response)
	{
		$json = json_decode($request->getBody(), true);
		if ($this->unregisterClientMessageJsonValidator->validateJson($json) === false)
		{
			$response->write("Incorrect JSON");
			return;
		}

		$this->clientsSourcesStoreRepository->remove(
			$json['clientId'],
			$json['sourceId']
		);

		$response->write("ok");
	}
}
