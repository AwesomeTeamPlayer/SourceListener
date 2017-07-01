<?php

namespace Domain;

use Domain\ValueObjects\DecodedClientId;
use GuzzleHttp\Psr7\Uri;

class ClientIdEncoderTest extends \PHPUnit_Framework_TestCase
{
	public function test_toJson()
	{
		$clientIdEncoder = new ClientIdEncoder();
		$json = $clientIdEncoder->toJson(
			new DecodedClientId(
				new Uri('http://google.com/abc?def=ghi'),
				'clientId'
			)
		);

		$this->assertEquals(
			'{"connectionId":"clientId","wsServer":"http:\/\/google.com\/abc?def=ghi"}',
			$json
		);
	}
}
