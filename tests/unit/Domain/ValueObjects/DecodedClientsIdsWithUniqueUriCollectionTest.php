<?php

namespace Domain\ValueObjects;

use Domain\ValueObjects\Exceptions\DecodedClientsIdsWithUniqueUriCollectionIsEmptyException;
use Domain\ValueObjects\Exceptions\IncorrectDecodedClientIdObjectException;
use GuzzleHttp\Psr7\Uri;

class DecodedClientsIdsWithUniqueUriCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function test_constructor_initialization()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection([
			new DecodedClientId(new Uri('http://google.com'), 'abc1'),
			new DecodedClientId(new Uri('http://google.com'), 'abc2'),
			new DecodedClientId(new Uri('http://google.com'), 'abc3'),
			new DecodedClientId(new Uri('http://google.com'), 'abc4'),
		]);
		$this->assertEquals(4, $collection->size());
	}

	public function test_constructor_initialization_with_non_unique_uri()
	{
		$this->setExpectedException(IncorrectDecodedClientIdObjectException::class);
		$collection = new DecodedClientsIdsWithUniqueUriCollection([
			new DecodedClientId(new Uri('http://google.com'), 'abc1'),
			new DecodedClientId(new Uri('http://google.com'), 'abc2'),
			new DecodedClientId(new Uri('http://bing.com'), 'xyz'),
			new DecodedClientId(new Uri('http://google.com'), 'abc4'),
		]);
		$this->assertEquals(3, $collection->size());
	}

	public function test_push_to_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->assertEquals(0, $collection->size());
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc123'));
		$this->assertEquals(1, $collection->size());
	}

	public function test_push_few_elements_to_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->assertEquals(0, $collection->size());
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc1'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc2'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc3'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc4'));
		$this->assertEquals(4, $collection->size());
	}

	public function test_push_with_different_uri_address()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc1'));

		$this->setExpectedException(IncorrectDecodedClientIdObjectException::class);
		$collection->push(new DecodedClientId(new Uri('http://bong.com'), 'abc2'));
	}

	public function test_getUri_on_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->setExpectedException(DecodedClientsIdsWithUniqueUriCollectionIsEmptyException::class);
		$collection->getUri();
	}

	public function test_getUri_on_not_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection([
			new DecodedClientId(new Uri('http://google.com'), 'abc1')
		]);
		$uri = $collection->getUri();

		$this->assertEquals(new Uri('http://google.com'), $uri);
	}

	public function test_getClientsIds_on_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->assertEmpty($collection->getClientsIds());
	}

	public function test_getClientsIds_on_not_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc1'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc2'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc3'));
		$collection->push(new DecodedClientId(new Uri('http://google.com'), 'abc4'));
		$this->assertEquals(
			[
				'abc1',
				'abc2',
				'abc3',
				'abc4',
			],
			$collection->getClientsIds()
		);
	}
}
