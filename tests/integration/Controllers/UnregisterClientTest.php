<?php

namespace tests\integration\Controllers;

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

class UnregisterClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var App
	 */
	private $app;

	/**
	 * @var MemoryClientsSourcesStoreRepository
	 */
	private $clientSourcesStoreRepository;

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

		$this->app = initApp(
			$container,
			$this->clientSourcesStoreRepository,
			new MockedHttpRequestToWsServerSender(),
			200
		);
	}

	public function test_unregister_client_with_empty_body()
	{
		$request = new Request(
			'POST',
			new Uri('http://domain.com/unregister-client'),
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

	public function test_unregister_client_with_incorrect_body()
	{
		$request = $this->buildRequest([
			'bla bla' => 'bla',
		]);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'Incorrect JSON',
			$response->getBody()->__toString()
		);
	}

	public function test_unregister_client_with_correct_body()
	{
		$this->clientSourcesStoreRepository->add('clientId123', 'sourceId');

		$request = $this->buildRequest([
			'clientId' => 'clientId123',
			'sourceId' => 'sourceId',
		]);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			['sourceId' => [] ],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	public function test_unregister_unregistered_client()
	{
		$request = $this->buildRequest([
			'clientId' => 'clientId123',
			'sourceId' => 'sourceId',
		]);
		$response = new Response();

		$this->app->process($request, $response);
		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			[],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	public function test_unregister_client_with_with_many_requests()
	{
		$this->clientSourcesStoreRepository->add('clientId_A', 'sourceId_1');
		$this->clientSourcesStoreRepository->add('clientId_B', 'sourceId_1');
		$this->clientSourcesStoreRepository->add('clientId_C', 'sourceId_2');
		$this->clientSourcesStoreRepository->add('clientId_D', 'sourceId_3');

		$request = $this->buildRequest([
			'clientId' => 'clientId_A',
			'sourceId' => 'sourceId_1'
		]);
		$this->app->process($request, new Response());

		$request = $this->buildRequest([
			'clientId' => 'clientId_B',
			'sourceId' => 'sourceId_1'
		]);
		$this->app->process($request, new Response());

		$request = $this->buildRequest([
			'clientId' => 'clientId_C',
			'sourceId' => 'sourceId_2'
		]);
		$this->app->process($request, new Response());

		$request = $this->buildRequest([
			'clientId' => 'clientId_D',
			'sourceId' => 'sourceId_3'
		]);
		$this->app->process($request, new Response());

		$this->assertEquals(
			[
				'sourceId_1' => [],
				'sourceId_2' => [],
				'sourceId_3' => [],
			],
			$this->clientSourcesStoreRepository->dump()
		);
	}

	private function buildRequest(array $jsonBodyArray) : Request
	{
		return new Request(
			'POST',
			new Uri('http://domain.com/unregister-client'),
			new Headers(),
			[],
			[],
			new StringToStream(json_encode($jsonBodyArray))
		);
	}
}
