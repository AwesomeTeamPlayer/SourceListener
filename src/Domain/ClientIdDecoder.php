<?php

namespace Domain;

use Domain\Exceptions\IncorrectClientIdValueException;
use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\DecodedClientsIdsCollection;
use GuzzleHttp\Psr7\Uri;

class ClientIdDecoder
{
	const WS_SERVER_LABEL = 'wsServer';
	const CONNECTION_ID = 'connectionId';

	/**
	 * @param array $clientsIds
	 *
	 * @return DecodedClientsIdsCollection
	 */
	public function decodeList(array $clientsIds) : DecodedClientsIdsCollection
	{
		$decodedClientsIds = [];

		foreach($clientsIds as $clientsId)
		{
			try
			{
				$decodedClientsIds[] = $this->decode($clientsId);
			}
			catch (IncorrectClientIdValueException $exception) {
			}
		}

		return new DecodedClientsIdsCollection($decodedClientsIds);
	}

	/**
	 * @param string $clientId
	 *
	 * @return DecodedClientId
	 *
	 * @throws IncorrectClientIdValueException
	 */
	public function decode(string $clientId) : DecodedClientId
	{
		$decodedClientIdJson = json_decode(urldecode($clientId), true);
		$this->tryValidate($decodedClientIdJson);

		return new DecodedClientId(
			new Uri($decodedClientIdJson[self::WS_SERVER_LABEL]),
			$decodedClientIdJson[self::CONNECTION_ID]
		);
	}

	/**
	 * @param array|null $decodedClientIdJson
	 *
	 * @return void
	 *
	 * @throws IncorrectClientIdValueException
	 */
	private function tryValidate(array $decodedClientIdJson = null)
	{
		if ($decodedClientIdJson === null)
		{
			throw new IncorrectClientIdValueException();
		}

		if (array_key_exists(self::WS_SERVER_LABEL, $decodedClientIdJson) === false ||
			array_key_exists(self::CONNECTION_ID, $decodedClientIdJson) === false)
		{
			throw new IncorrectClientIdValueException();
		}

		if (is_string($decodedClientIdJson[self::WS_SERVER_LABEL]) === false)
		{
			throw new IncorrectClientIdValueException();
		}

		if (is_string($decodedClientIdJson[self::CONNECTION_ID]) === false)
		{
			throw new IncorrectClientIdValueException();
		}

		if (filter_var($decodedClientIdJson[self::WS_SERVER_LABEL], FILTER_VALIDATE_URL) === false)
		{
			throw new IncorrectClientIdValueException();
		}
	}
}
