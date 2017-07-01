<?php

namespace Controllers\Validators;

class RegisterClientMessageJsonValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_validateJson($json, $expectedResult)
	{
		$validator = new RegisterClientMessageJsonValidator();

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
				[RegisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc'],
				false
			],
			[
				[RegisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'],
				false
			],
			[
				[
					RegisterClientMessageJsonValidator::CLIENT_ID_LABEL => 123,
					RegisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'
				],
				false
			],
			[
				[
					RegisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc',
					RegisterClientMessageJsonValidator::SOURCE_ID_LABEL => 123
				],
				false
			],
			[
				[
					RegisterClientMessageJsonValidator::CLIENT_ID_LABEL => 'abc',
					RegisterClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'
				],
				true
			]
		];
	}
}
