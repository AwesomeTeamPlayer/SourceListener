<?php

namespace Domain\ValueObjects;

use Domain\ValueObjects\Exceptions\IncorrectDecodedClientIdObjectException;
use GuzzleHttp\Psr7\Uri;

class DecodedClientsIdsWithUniqueUriCollectionTest extends \PHPUnit_Framework_TestCase
{
	public function test_push_to_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->assertEquals(0, $collection->size());
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc123'));
		$this->assertEquals(1, $collection->size());
	}

	public function test_push_few_elements_to_empty_collection()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$this->assertEquals(0, $collection->size());
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc1'));
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc2'));
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc3'));
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc4'));
		$this->assertEquals(4, $collection->size());
	}

	public function test_push_with_different_uri_address()
	{
		$collection = new DecodedClientsIdsWithUniqueUriCollection();
		$collection->push(new DecodedClientId(new Uri('http://google.om'), 'abc1'));

		$this->setExpectedException(IncorrectDecodedClientIdObjectException::class);
		$collection->push(new DecodedClientId(new Uri('http://bong.com'), 'abc2'));
	}
}
