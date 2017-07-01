<?php

namespace Domain;

use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\InformClientsMessage;
use Domain\ValueObjects\MessageToWsServer;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

class MessageSenderTest extends \PHPUnit_Framework_TestCase
{
	public function test_sendMessage_when_clients_list_is_empty()
	{
		$httpSender = $this->getMockBuilder(HttpRequestToWsServerSenderInterface::class)
			->setMethods(['sendRequest'])
			->getMock();
		$httpSender->expects($this->never())->method('sendRequest');

		$messageSender = new MessageSender(
			new ClientIdDecoder(),
			new DecodedClientsIdsCollectionByUniqueUriGrouper(),
			$httpSender
		);

		$messageSender->sendMessage(
			new InformClientsMessage(
				'sourceId',
				[ 'messageContent' ]
			),
			[]
		);
	}

	public function test_sendMessage_when_clients_list_has_two_element_with_the_same_uri()
	{
		$clientUri = new Uri('http://google.com');
		$sourceId = 'sourceId';
		$messageContent = [ 'messageContent' ];

		$httpSender = $this->getMockBuilder(HttpRequestToWsServerSenderInterface::class)
			->setMethods(['sendRequest'])
			->getMock();
		$httpSender->expects($this->once())->method('sendRequest')
			->willReturnCallback(function(UriInterface $uri, MessageToWsServer $messageToWsServer) use ($clientUri, $sourceId, $messageContent){
				$this->assertEquals($clientUri, $uri);
				$this->assertEquals(
					new MessageToWsServer(
						['connection123', 'connection456'],
						$sourceId,
						$messageContent
					),
					$messageToWsServer
				);
			});

		$messageSender = new MessageSender(
			new ClientIdDecoder(),
			new DecodedClientsIdsCollectionByUniqueUriGrouper(),
			$httpSender
		);

		$clientIdEncoder = new ClientIdEncoder();

		$messageSender->sendMessage(
			new InformClientsMessage(
				$sourceId,
				$messageContent
			),
			[
				$clientIdEncoder->toJson(new DecodedClientId($clientUri, 'connection123')),
				$clientIdEncoder->toJson(new DecodedClientId($clientUri, 'connection456')),
			]
		);
	}

	public function test_sendMessage_when_clients_list_has_two_element_with_difference_uris()
	{
		$clientUri[0] = new Uri('http://google.com');
		$clientUri[1] = new Uri('http://bing.com');
		$sourceId = 'sourceId';
		$messageContent = [ 'messageContent' ];

		$executionResult = [];

		$httpSender = $this->getMockBuilder(HttpRequestToWsServerSenderInterface::class)
			->setMethods(['sendRequest'])
			->getMock();
		$httpSender->expects($this->exactly(2))->method('sendRequest')
			->willReturnCallback(function(UriInterface $uri, MessageToWsServer $messageToWsServer)
				use ($clientUri, $sourceId, $messageContent, &$executionResult)
			{
				$executionResult[$uri->__toString()] = $messageToWsServer;
			});

		$messageSender = new MessageSender(
			new ClientIdDecoder(),
			new DecodedClientsIdsCollectionByUniqueUriGrouper(),
			$httpSender
		);

		$clientIdEncoder = new ClientIdEncoder();

		$messageSender->sendMessage(
			new InformClientsMessage(
				$sourceId,
				$messageContent
			),
			[
				$clientIdEncoder->toJson(new DecodedClientId($clientUri[0], 'connection123')),
				$clientIdEncoder->toJson(new DecodedClientId($clientUri[1], 'connection456')),
			]
		);

		$this->assertEquals(
			[
				$clientUri[0]->__toString() => new MessageToWsServer(
					['connection123'],
					$sourceId,
					$messageContent
				),
				$clientUri[1]->__toString() => new MessageToWsServer(
					['connection456'],
					$sourceId,
					$messageContent
				),
			],
			$executionResult
		);
	}
}
