<?php

namespace Domain;

use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\DecodedClientsIdsCollection;
use Domain\ValueObjects\DecodedClientsIdsWithUniqueUriCollection;
use GuzzleHttp\Psr7\Uri;

class DecodedClientsIdsCollectionByUniqueUriGrouperTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @dataProvider groupDataProvider
	 */
	public function test_group($decodedClientsIdsCollection, $expectedResult)
	{
		$decodedClientsIdsCollectionByUniqueUriGrouper = new DecodedClientsIdsCollectionByUniqueUriGrouper();
		$result = $decodedClientsIdsCollectionByUniqueUriGrouper->group($decodedClientsIdsCollection);

		$this->assertEquals($expectedResult, $result);
	}

	public function groupDataProvider()
	{
		return [
			[
				new DecodedClientsIdsCollection([]),
				[]
			],
			[
				new DecodedClientsIdsCollection([
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					),
					new DecodedClientId(
						new Uri('http://bing.com'),
						'def456'
					)
				]),
				[
					'http://google.com' => new DecodedClientsIdsWithUniqueUriCollection([
							new DecodedClientId(
								new Uri('http://google.com'),
								'abc123'
							)]
					),
					'http://bing.com' => new DecodedClientsIdsWithUniqueUriCollection([
							new DecodedClientId(
								new Uri('http://bing.com'),
								'def456'
							)]
					),
				]
			],
			[
				new DecodedClientsIdsCollection([
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					),
					new DecodedClientId(
						new Uri('http://bing.com'),
						'def456'
					),
					new DecodedClientId(
						new Uri('http://google.com'),
						'xxx'
					),
				]),
				[
					'http://google.com' => new DecodedClientsIdsWithUniqueUriCollection([
						new DecodedClientId(
							new Uri('http://google.com'),
							'abc123'
						),
						new DecodedClientId(
							new Uri('http://google.com'),
							'xxx'
						),
					]),
					'http://bing.com' => new DecodedClientsIdsWithUniqueUriCollection([
						new DecodedClientId(
							new Uri('http://bing.com'),
							'def456'
						)
					])
				]
			],
		];
	}
}
