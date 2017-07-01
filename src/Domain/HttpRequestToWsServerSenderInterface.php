<?php

namespace Domain;

use Domain\ValueObjects\MessageToWsServer;
use Psr\Http\Message\UriInterface;

interface HttpRequestToWsServerSenderInterface
{
	/**
	 * @param UriInterface $uri
	 * @param MessageToWsServer $messageToWsServer
	 *
	 * @return void
	 */
	public function sendRequest(UriInterface $uri, MessageToWsServer $messageToWsServer);
}
