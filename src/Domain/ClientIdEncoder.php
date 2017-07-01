<?php

namespace Domain;

use Domain\ValueObjects\DecodedClientId;

class ClientIdEncoder
{
	const WS_SERVER_LABEL = 'wsServer';
	const CONNECTION_ID = 'connectionId';

	/**
	 * @param DecodedClientId $decodedClientId
	 *
	 * @return string
	 */
	public function toJson(DecodedClientId $decodedClientId) : string
	{
		return json_encode([
			'connectionId' => $decodedClientId->connectionId(),
			'wsServer' => $decodedClientId->uri()->__toString(),
		]);
	}
}
