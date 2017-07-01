<?php

namespace Domain;

use Domain\Adapters\HttpRequestToWsServerSenderInterface;
use Domain\ValueObjects\DecodedClientsIdsWithUniqueUriCollection;
use Domain\ValueObjects\Exceptions\DecodedClientsIdsWithUniqueUriCollectionIsEmptyException;
use Domain\ValueObjects\InformClientsMessage;
use Domain\ValueObjects\MessageToWsServer;
use Psr\Http\Message\UriInterface;

class MessageSender
{
	/**
	 * @var ClientIdDecoder
	 */
	private $clientIdDecoder;

	/**
	 * @var DecodedClientsIdsCollectionByUniqueUriGrouper
	 */
	private $decodedClientsIdsCollectionByUniqueUriGrouper;

	/**
	 * @var HttpRequestToWsServerSenderInterface
	 */
	private $httpRequestToWsServerSender;

	/**
	 * @param ClientIdDecoder $clientIdDecoder
	 * @param DecodedClientsIdsCollectionByUniqueUriGrouper $decodedClientsIdsCollectionByUniqueUriGrouper
	 * @param HttpRequestToWsServerSenderInterface $httpRequestToWsServerSender
	 */
	public function __construct(
		ClientIdDecoder $clientIdDecoder,
		DecodedClientsIdsCollectionByUniqueUriGrouper $decodedClientsIdsCollectionByUniqueUriGrouper,
		HttpRequestToWsServerSenderInterface $httpRequestToWsServerSender
	)
	{
		$this->clientIdDecoder = $clientIdDecoder;
		$this->decodedClientsIdsCollectionByUniqueUriGrouper = $decodedClientsIdsCollectionByUniqueUriGrouper;
		$this->httpRequestToWsServerSender = $httpRequestToWsServerSender;
	}

	/**
	 * @param InformClientsMessage $informClientsMessage
	 * @param string[] $clientsIds
	 *
	 * @return void
	 *
	 */
	public function sendMessage(
		InformClientsMessage $informClientsMessage,
		array $clientsIds
	)
	{
		$decodedClientsIdsCollection = $this->clientIdDecoder->decodeList($clientsIds);
		$decodedClientsIdsWithUniqueUriCollectionsArray = $this->decodedClientsIdsCollectionByUniqueUriGrouper->group($decodedClientsIdsCollection);

		foreach ($decodedClientsIdsWithUniqueUriCollectionsArray as $decodedClientsIdsWithUniqueUriCollection)
		{
			$this->sendGropedMessage(
				$informClientsMessage,
				$decodedClientsIdsWithUniqueUriCollection
			);
		}
	}

	/**
	 * @param InformClientsMessage $informClientsMessage
	 * @param DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection
	 *
	 * @return void
	 */
	private function sendGropedMessage(
		InformClientsMessage $informClientsMessage,
		DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection
	)
	{
		if ($decodedClientsIdsWithUniqueUriCollection->size() === 0)
		{
			return;
		}

		$this->httpRequestToWsServerSender->sendRequest(
			$this->tryGetUriFromNotEmptyColection($decodedClientsIdsWithUniqueUriCollection),
			new MessageToWsServer(
				$decodedClientsIdsWithUniqueUriCollection->getClientsIds(),
				$informClientsMessage->sourceId(),
				$informClientsMessage->message()
			)
		);
	}

	/**
	 * @param DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection
	 *
	 * @return UriInterface
	 */
	private function tryGetUriFromNotEmptyColection(
		DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection
	) : UriInterface
	{
		try
		{
			return $decodedClientsIdsWithUniqueUriCollection->getUri();
		}
		catch (DecodedClientsIdsWithUniqueUriCollectionIsEmptyException $exception)
		{
		}
	}
}
