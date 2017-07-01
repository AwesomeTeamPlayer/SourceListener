<?php

namespace Domain\ValueObjects;

use GuzzleHttp\Psr7\Uri;

class DecodedClientsIdsCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider sortDataProvider
	 */
	public function test_sort($decodedClientsIds, $expectedOrderedDecodedClientsIdsCollection)
	{
		$collection = new DecodedClientsIdsCollection($decodedClientsIds);
		$result = $collection->sortCollection()->toArray();

		$this->assertEquals($expectedOrderedDecodedClientsIdsCollection, $result);
	}

	public function sortDataProvider()
	{
		return [
			[
				[],
				[]
			],
			[
				[
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					)
				],
				[
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					)
				]
			],
			[
				[
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					),
					new DecodedClientId(
						new Uri('http://bing.com'),
						'def456'
					)
				],
				[
					new DecodedClientId(
						new Uri('http://bing.com'),
						'def456'
					),
					new DecodedClientId(
						new Uri('http://google.com'),
						'abc123'
					)
				]
			],
		];
	}

}
