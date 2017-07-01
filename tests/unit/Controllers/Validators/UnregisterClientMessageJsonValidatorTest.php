<?php

namespace Controllers\Validators;

class UnregisterClientMessageJsonValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_validateJson($json, $expectedResult)
	{
		$validator = new UnregisterClientMessageJsonValidator();

		$result = $validator->validateJson($json);
		$this->assertEquals($expectedResult, $result);
	}

	public function dataProvider()
	{
		return [
			[
				null,
				false
			],
			[
				[UnregisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc'],
				false
			],
			[
				[UnregisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'],
				false
			],
			[
				[
					UnregisterClientMessageJsonValidator::CLIENT_ID_LABEL => 123,
					UnregisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'
				],
				false
			],
			[
				[
					UnregisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc',
					UnregisterClientMessageJsonValidator::SOURCE_ID_LABEL => 123
				],
				false
			],
			[
				[
					UnregisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc',
					UnregisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'
				],
				true
			]
		];
	}
}
