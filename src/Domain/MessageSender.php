<?php

namespace Domain;

use Domain\ClientIdDecoder;
use Domain\ValueObjects\DecodedClientsIdsCollection;

class MessageSender
{
	/**
	 * @var ClientIdDecoder
	 */
	private $clientIdDecoder;

	/**
	 * @param array $message
	 * @param string[] $clientsIds
	 *
	 * @return void
	 *
	 */
	public function sendMessage(
		array $message,
		array $clientsIds
	)
	{
		$decodedClientsIdsCollection = $this->clientIdDecoder->decodeList($clientsIds);
		$groupedDecodedClientsIdsArray = $decodedClientsIdsCollection->group();

		foreach ($groupedDecodedClientsIdsArray as $uri => $decodedClientsIds)
		{

		}
		//  todo...
	}

	private function sendGropedMessage(array $decodedClientsIds)
	{

	}

}
