<?php

namespace Controllers;

use Controllers\Validators\RegisterClientMessageJsonValidator;
use Domain\ClientsSourcesStoreRepositoryInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class RegisterClientController implements ControllerInterface
{
	/**
	 * @var ClientsSourcesStoreRepositoryInterface
	 */
	private $clientsSourcesStoreRepository;

	/**
	 * @var RegisterClientMessageJsonValidator
	 */
	private $registerClientMessageJsonValidator;

	/**
	 * @param ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository
	 * @param RegisterClientMessageJsonValidator $registerClientMessageJsonValidator
	 */
	public function __construct(
		ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository,
		RegisterClientMessageJsonValidator $registerClientMessageJsonValidator)

	{
		$this->clientsSourcesStoreRepository = $clientsSourcesStoreRepository;
		$this->registerClientMessageJsonValidator = $registerClientMessageJsonValidator;
	}

	/**
	 * @param Request $request
	 * @param Response $response
	 *
	 * @return void
	 */
	public function run(Request $request, Response $response)
	{
		$json = json_decode($request->getBody(), true);
		if ($this->registerClientMessageJsonValidator->validateJson($json) === false)
		{
			$response->write("Incorrect JSON");
			return;
		}

		$this->clientsSourcesStoreRepository->add(
			$json['clientId'],
			$json['sourceId']
		);

		$response->write("ok");
	}
}
