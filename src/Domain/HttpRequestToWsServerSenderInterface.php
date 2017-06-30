<?php

namespace Domain;

use Domain\ValueObjects\MessageToWsServer;
use Psr\Http\Message\UriInterface;

interface HttpRequestToWsServerSenderInterface
{
	public function sendRequest(UriInterface $uri, MessageToWsServer $messageToWsServer);
}
