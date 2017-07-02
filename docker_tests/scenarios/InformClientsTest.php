<?php

use Domain\ClientIdEncoder;
use GuzzleHttp\Client;
use Slim\Http\Request;
use GuzzleHttp\Psr7\Uri;
use Slim\Http\Headers;
use tests\helpers\StringToStream;

require_once __DIR__ . '/../../tests/helpers/StringToStream.php';

class InformClientsTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var string
	 */
	private $domain;

	/**
	* @var ClientIdEncoder
	*/
	private $clientIdEncoder;

	/**
	 * @var Redis
	 */
	private $redis;

	public function setUp()
	{
		$this->domain = getenv('DOMAIN');
		$redisHost = getenv('REDIS_HOST');
		$redisPort = getenv('REDIS_PORT');

		$this->clientIdEncoder = new ClientIdEncoder();

		$this->redis = new Redis();
		$this->redis->connect($redisHost, $redisPort);
	}

	public function tearDown()
	{
		$this->redis->flushAll();
	}

	public function test_inform_client_withs_empty_body()
	{
		$request = new Request(
			'POST',
			new Uri('http://' . $this->domain . '/inform-clients'),
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

	public function test_inform_clients_with_incorrect_body()
	{
		$request = $this->buildRequest([
			'bla bla' => 'bla',
			'a' => 'b'
		]);

		$client = new Client();
		$response = $client->send($request);

		$this->assertEquals(
			'Incorrect JSON',
			$response->getBody()->__toString()
		);
	}

	private function buildRequest(array $jsonBodyArray) : Request
	{
		$json = json_encode($jsonBodyArray);

		return new Request(
			'POST',
			new Uri('http://' . $this->domain . '/inform-clients'),
			new Headers(),
			[],
			[],
			new StringToStream($json)
		);
	}

}
