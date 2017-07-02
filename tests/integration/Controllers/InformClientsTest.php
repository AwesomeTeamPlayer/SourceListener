<?php

namespace tests\integration\Controllers;

use Domain\ClientIdEncoder;
use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\MessageToWsServer;
use GuzzleHttp\Psr7\Uri;
use Slim\App;
use Slim\Container;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use tests\helpers\MemoryClientsSourcesStoreRepository;
use tests\helpers\MockedHttpRequestToWsServerSender;
use tests\helpers\StringToStream;

require_once __DIR__ . '/../../../src/initApp.php';
require_once __DIR__ . '/../../../tests/helpers/MemoryClientsSourcesStoreRepository.php';
require_once __DIR__ . '/../../../tests/helpers/MockedHttpRequestToWsServerSender.php';
require_once __DIR__ . '/../../../tests/helpers/StringToStream.php';

class InformClientsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var App
	 */
	private $app;

	/**
	 * @var MemoryClientsSourcesStoreRepository
	 */
	private $clientSourcesStoreRepository;

	/**
	 * @var MockedHttpRequestToWsServerSender
	 */
	private $mockedHttpRequestToWsServerSender;

	/**
	 * @var ClientIdEncoder
	 */
	private $clientIdEncoder;

	public function setUp()
	{
		$container = new Container(
			array_merge(
				[
					'settings' => [
						'displayErrorDetails' => true,
					]
				]
			)
		);

		$this->clientSourcesStoreRepository = new MemoryClientsSourcesStoreRepository();
		$this->mockedHttpRequestToWsServerSender = new MockedHttpRequestToWsServerSender();
		$this->clientIdEncoder = new ClientIdEncoder();

		$this->app = initApp(
			$container,
			$this->clientSourcesStoreRepository,
			$this->mockedHttpRequestToWsServerSender,
			200,
			[]
		);
	}

	public function test_inform_client_withs_empty_body()
	{
		$request = new Request(
			'POST',
			new Uri('http://domain.com/inform-clients'),
			new Headers(),
			[],
			[],
			new StringToStream('')
		);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'Incorrect JSON',
			$response->getBody()->__toString()
		);
	}

	public function test_inform_clients_with_incorrect_body()
	{
		$request = $this->buildRequest([
			'bla bla' => 'bla',
			'a' => 'b'
		]);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'Incorrect JSON',
			$response->getBody()->__toString()
		);
	}

	public function test_inform_clients_with_correct_body()
	{
		$clientJsonId = $this->clientIdEncoder->toJson(new DecodedClientId(new Uri('http://some.domain/endpoint'), 'connectionId'));
		$this->clientSourcesStoreRepository->add($clientJsonId, 'sourceId');

		$request = $this->buildRequest([
			'sourceId' => 'sourceId',
			'message' => [
				'this' => ['is' => 'message', 'content' => '!!!']
			],
		]);
		$response = new Response();

		$this->app->process($request, $response);

		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			[
				'http://some.domain/endpoint' => [
					new MessageToWsServer(
						[ 'connectionId' ],
						'sourceId',
						[
							'this' => ['is' => 'message', 'content' => '!!!']
						]
					)
				]
			],
			$this->mockedHttpRequestToWsServerSender->dump()
		);

		$this->assertEquals(
			['sourceId' => [$clientJsonId] ],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	public function test_inform_clients_with_unregistered_client()
	{
		$clientJsonId = $this->clientIdEncoder->toJson(new DecodedClientId(new Uri('http://some.domain/endpoint'), 'connectionId'));
		$this->clientSourcesStoreRepository->add($clientJsonId, 'sourceId');

		$request = $this->buildRequest([
			'sourceId' => 'differentSourceId',
			'message' => [
				'this' => ['is' => 'message', 'content' => '!!!']
			],
		]);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			[],
			$this->mockedHttpRequestToWsServerSender->dump()
		);

		$this->assertEquals(
			['sourceId' => [$clientJsonId] ],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	public function test_inform_clients_with_many_requests()
	{
		$clientJsonId[0] = $this->clientIdEncoder->toJson(new DecodedClientId(new Uri('http://some.domain/endpoint'), 'connectionId_0'));
		$clientJsonId[1] = $this->clientIdEncoder->toJson(new DecodedClientId(new Uri('http://some.domain/endpoint'), 'connectionId_1'));
		$clientJsonId[2] = $this->clientIdEncoder->toJson(new DecodedClientId(new Uri('http://some.other.domain/endpoint'), 'connectionId_2'));

		$this->clientSourcesStoreRepository->add($clientJsonId[0], 'sourceId_1');
		$this->clientSourcesStoreRepository->add($clientJsonId[1], 'sourceId_1');
		$this->clientSourcesStoreRepository->add($clientJsonId[1], 'sourceId_2');
		$this->clientSourcesStoreRepository->add($clientJsonId[2], 'sourceId_3');

		$request = $this->buildRequest([
			'sourceId' => 'sourceId_1',
			'message' => [
				'this' => ['is' => 'first message', 'content' => '!!!']
			],
		]);
		$this->app->process($request, new Response());

		$request = $this->buildRequest([
			'sourceId' => 'sourceId_2',
			'message' => [
				'this' => ['is' => 'second message', 'content' => '!!!']
			],
		]);
		$this->app->process($request, new Response());

		$request = $this->buildRequest([
			'sourceId' => 'sourceId_3',
			'message' => [
				'this' => ['is' => 'third message', 'content' => '!!!']
			],
		]);
		$this->app->process($request, new Response());

		$this->assertEquals(
			[
				'http://some.domain/endpoint' => [
					new MessageToWsServer(
						['connectionId_0', 'connectionId_1'],
						'sourceId_1',
						[
							'this' => ['is' => 'first message', 'content' => '!!!']
						]
					),
					new MessageToWsServer(
						['connectionId_1'],
						'sourceId_2',
						[
							'this' => ['is' => 'second message', 'content' => '!!!']
						]
					),
				],
				'http://some.other.domain/endpoint' => [
					new MessageToWsServer(
						['connectionId_2'],
						'sourceId_3',
						[
							'this' => ['is' => 'third message', 'content' => '!!!']
						]
					),
				],
			],
			$this->mockedHttpRequestToWsServerSender->dump()
		);

		$this->assertEquals(
			[
				'sourceId_1' => [ $clientJsonId[0], $clientJsonId[1] ],
				'sourceId_2' => [ $clientJsonId[1] ],
				'sourceId_3' => [ $clientJsonId[2] ],
			],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	private function buildRequest(array $jsonBodyArray) : Request
	{
		$json = json_encode($jsonBodyArray);

		return new Request(
			'POST',
			new Uri('http://domain.com/inform-clients'),
			new Headers(),
			[],
			[],
			new StringToStream($json)
		);
	}
}
