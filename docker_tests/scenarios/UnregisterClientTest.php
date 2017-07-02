<?php

use Domain\ClientIdEncoder;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Slim\Http\Headers;
use Slim\Http\Request;
use tests\helpers\StringToStream;

require_once __DIR__ . '/../../src/initApp.php';

class UnregisterClientTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var string
	 */
	private $domain;

	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var ClientIdEncoder
	 */
	private $clientIdEncoder;

	public function setUp()
	{
		$this->domain = getenv('DOMAIN');
		$redisHost = getenv('REDIS_HOST');
		$redisPort = getenv('REDIS_PORT');

		$this->clientIdEncoder = new ClientIdEncoder();

		$this->redis = new \Redis();
		$this->redis->connect($redisHost, $redisPort);
	}

	public function tearDown()
	{
		$this->redis->flushAll();
	}

	public function test_unregister_client_with_empty_body()
	{
		$request = new Request(
			'POST',
			new Uri('http://' . $this->domain . '/unregister-client'),
			new Headers(),
			[],
			[],
			new StringToStream('')
		);

		$client = new Client();
		$response = $client->send($request);

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

		$client = new Client();
		$response = $client->send($request);

		$this->assertEquals(
			'Incorrect JSON',
			$response->getBody()->__toString()
		);
	}

	public function test_unregister_client_with_correct_body()
	{
		$this->redis->lPush('clientId123', 'sourceId');

		$request = $this->buildRequest([
			'clientId' => 'clientId123',
			'sourceId' => 'sourceId',
		]);

		$client = new Client();
		$response = $client->send($request);

		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			[],
			$this->redis->lRange('sourceId', 0, 10)
		);
	}

	public function test_unregister_unregistered_client()
	{
		$request = $this->buildRequest([
			'clientId' => 'clientId123',
			'sourceId' => 'sourceId',
		]);

		$client = new Client();
		$response = $client->send($request);

		$this->assertEquals(
			'ok',
			$response->getBody()->__toString()
		);

		$this->assertEquals(
			[],
			$this->redis->lRange('sourceId', 0, 10)
		);
	}

	public function test_unregister_client_with_with_many_requests()
	{
		$this->redis->lPush('sourceId_1', 'clientId_A');
		$this->redis->lPush('sourceId_1', 'clientId_B');
		$this->redis->lPush('sourceId_2', 'clientId_C');
		$this->redis->lPush('sourceId_3', 'clientId_D');

		$client = new Client();

		$request = $this->buildRequest([
			'clientId' => 'clientId_A',
			'sourceId' => 'sourceId_1'
		]);
		$client->send($request);

		$request = $this->buildRequest([
			'clientId' => 'clientId_B',
			'sourceId' => 'sourceId_1'
		]);
		$client->send($request);

		$request = $this->buildRequest([
			'clientId' => 'clientId_C',
			'sourceId' => 'sourceId_2'
		]);
		$client->send($request);

		$request = $this->buildRequest([
			'clientId' => 'clientId_D',
			'sourceId' => 'sourceId_3'
		]);
		$client->send($request);

		$this->assertEquals(
			[],
			$this->redis->lRange('sourceId_1', 0, 10)
		);

		$this->assertEquals(
			[],
			$this->redis->lRange('sourceId_2', 0, 10)
		);

		$this->assertEquals(
			[],
			$this->redis->lRange('sourceId_3', 0, 10)
		);
	}

	private function buildRequest(array $jsonBodyArray) : Request
	{
		return new Request(
			'POST',
			new Uri('http://' . $this->domain . '/unregister-client'),
			new Headers(),
			[],
			[],
			new StringToStream(json_encode($jsonBodyArray))
		);
	}
}
