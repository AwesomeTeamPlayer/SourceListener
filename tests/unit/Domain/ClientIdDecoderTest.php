<?php

namespace Domain;

use Domain\Exceptions\IncorrectClientIdValueException;
use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\DecodedClientsIdsCollection;
use GuzzleHttp\Psr7\Uri;

class ClientIdDecoderTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider decodeDataProvider
	 */
	public function test_decode($clientId, $willThrowException, $expectedResult)
	{
		$decoder = new ClientIdDecoder();

		if ($willThrowException)
		{
			$this->setExpectedException(IncorrectClientIdValueException::class);
			$decoder->decode($clientId);
		}
		else
		{
			$result = $decoder->decode($clientId);
			$this->assertEquals($expectedResult, $result);
		}
	}

	public function decodeDataProvider()
	{
		return [
			[
				'asdfsadf',
				true,
				null
			],
			[
				urlencode(json_encode([
					ClientIdDecoder::WS_SERVER_LABEL => 'aaa'
				])),
				true,
				null
			],
			[
				urlencode(json_encode([ClientIdDecoder::CONNECTION_ID => 'aaa'])),
				true,
				null
			],
			[
				urlencode(json_encode([
					ClientIdDecoder::WS_SERVER_LABEL => 123,
					ClientIdDecoder::CONNECTION_ID => 'aaa'
				])),
				true,
				null
			],[
				urlencode(json_encode([
					ClientIdDecoder::WS_SERVER_LABEL => 'aaa',
					ClientIdDecoder::CONNECTION_ID => 123
				])),
				true,
				null
			],
			[
				urlencode(json_encode([
					ClientIdDecoder::WS_SERVER_LABEL => 'aaa',
					ClientIdDecoder::CONNECTION_ID => 'bbb'
				])),
				true,
				null
			],
			[
				urlencode(json_encode([
					ClientIdDecoder::WS_SERVER_LABEL => 'http://zzz.xxx.yyy/abc?def=ghi',
					ClientIdDecoder::CONNECTION_ID => 'bbb'
				])),
				false,
				new DecodedClientId(
					new Uri('http://zzz.xxx.yyy/abc?def=ghi'),
					'bbb'
				)
			],
		];
	}

	/**
	 * @dataProvider decodeListDataProvider
	 */
	public function test_decodeList($clientsIds, $expectedResult)
	{
		$decoder = new ClientIdDecoder();

		$result = $decoder->decodeList($clientsIds);
		$this->assertEquals($expectedResult, $result);
	}

	public function decodeListDataProvider()
	{
		return [
			[
				[
					urlencode(json_encode([
						ClientIdDecoder::WS_SERVER_LABEL => 'http://zzz.xxx.yyy/abc?def=ghi',
						ClientIdDecoder::CONNECTION_ID => 'bbb'
					])),
					urlencode(json_encode([
						ClientIdDecoder::WS_SERVER_LABEL => 'http://xyz.abc',
						ClientIdDecoder::CONNECTION_ID => 'aaa'
					])),
				],
				new DecodedClientsIdsCollection([
					new DecodedClientId(
						new Uri('http://zzz.xxx.yyy/abc?def=ghi'),
						'bbb'
					),
					new DecodedClientId(
						new Uri('http://xyz.abc'),
						'aaa'
					)
				])
			],
		];
	}
}
