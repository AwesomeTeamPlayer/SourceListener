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

	/**
	 * @dataProvider groupDataProvider
	 */
	public function test_group($decodedClientsIds, $expectedResult)
	{
		$collection = new DecodedClientsIdsCollection($decodedClientsIds);
		$result = $collection->group();

		$this->assertEquals($expectedResult, $result);
	}

	public function groupDataProvider()
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
					),
					new DecodedClientId(
						new Uri('http://bing.com'),
						'def456'
					)
				],
				[
					'http://google.com' => [
						new DecodedClientId(
							new Uri('http://google.com'),
							'abc123'
						),
					],
					'http://bing.com' => [
						new DecodedClientId(
							new Uri('http://bing.com'),
							'def456'
						)
					]
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
					),
					new DecodedClientId(
						new Uri('http://google.com'),
						'xxx'
					),
				],
				[
					'http://google.com' => [
						new DecodedClientId(
							new Uri('http://google.com'),
							'abc123'
						),
						new DecodedClientId(
							new Uri('http://google.com'),
							'xxx'
						),
					],
					'http://bing.com' => [
						new DecodedClientId(
							new Uri('http://bing.com'),
							'def456'
						)
					]
				]
			],
		];
	}
}
