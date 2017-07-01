<?php

namespace Adapters;

use Domain\Adapters\HttpRequestToWsServerSenderInterface;
use Domain\ValueObjects\MessageToWsServer;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\UriInterface;

class HttpRequestToWsServerSender implements HttpRequestToWsServerSenderInterface
{

	/**
	 * @param UriInterface $uri
	 * @param MessageToWsServer $messageToWsServer
	 *
	 * @return void
	 */
	public function sendRequest(
		UriInterface $uri,
		MessageToWsServer $messageToWsServer
	)
	{
		$client = new Client();
		$request = new Request(
			'POST',
			$uri
			[],
			json_encode($messageToWsServer->message())
		);
		$client->sendAsync($request)->wait();
	}
}
