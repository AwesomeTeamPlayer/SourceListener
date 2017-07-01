<?php

namespace tests\helpers;

use Domain\Adapters\HttpRequestToWsServerSenderInterface;
use Domain\ValueObjects\MessageToWsServer;
use Psr\Http\Message\UriInterface;

class MockedHttpRequestToWsServerSender implements HttpRequestToWsServerSenderInterface
{

	/**
	 * Index is URI string.
	 *
	 * @var MessageToWsServer[][]
	 */
	private $messagesToSend = [];

	/**
	 * @return MessageToWsServer[][]
	 */
	public function dump() : array
	{
		return $this->messagesToSend;
	}

	/**
	 * @param UriInterface $uri
	 * @param MessageToWsServer $messageToWsServer
	 *
	 * @return void
	 */
	public function sendRequest(UriInterface $uri, MessageToWsServer $messageToWsServer)
	{
		$this->messagesToSend[$uri->__toString()][] = $messageToWsServer;
	}
}
