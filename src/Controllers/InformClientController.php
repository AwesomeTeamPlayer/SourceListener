<?php

namespace Controllers;

use Controllers\Validators\InformClientMessageJsonValidator;
use Domain\InformClientService;
use Domain\ValueObjects\InformClientsMessage;
use Slim\Http\Request;
use Slim\Http\Response;

class InformClientController implements ControllerInterface
{

	/**
	 * @var InformClientMessageJsonValidator
	 */
	private $informClientMessageJsonValidator;

	/**
	 * @var InformClientService
	 */
	private $informClientService;

	/**
	 * @param InformClientMessageJsonValidator $informClientMessageJsonValidator
	 * @param InformClientService $informClientService
	 */
	public function __construct(
		InformClientMessageJsonValidator $informClientMessageJsonValidator,
		InformClientService $informClientService
	)
	{
		$this->informClientMessageJsonValidator = $informClientMessageJsonValidator;
		$this->informClientService = $informClientService;
	}

	public function run(Request $request, Response $response)
	{
		$json = json_decode($request->getBody(), true);
		if ($this->informClientMessageJsonValidator->validateJson($json) === false)
		{
			$response->write("Incorrect JSON");
			return;
		}

		$this->informClientService->informClient(
			new InformClientsMessage(
				$json[InformClientMessageJsonValidator::SOURCE_ID_LABEL],
				$json[InformClientMessageJsonValidator::MESSAGE_LABEL]
			)
		);

		$response->write("ok");
	}
}
