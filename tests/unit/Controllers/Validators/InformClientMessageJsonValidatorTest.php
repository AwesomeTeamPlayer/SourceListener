<?php

namespace Controllers\Validators;

class InformClientMessageJsonValidatorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider dataProvider
	 */
	public function test_validateJson($json, $expectedResult)
	{
		$validator = new InformClientMessageJsonValidator();

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
				[InformClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc'],
				false
			],
			[
				[InformClientMessageJsonValidator::MESSAGE_LABEL => []],
				false
			],
			[
				[
					InformClientMessageJsonValidator::SOURCE_ID_LABEL => 123,
					InformClientMessageJsonValidator::MESSAGE_LABEL => []
				],
				false
			],
			[
				[
					InformClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc',
					InformClientMessageJsonValidator::MESSAGE_LABEL => 123
				],
				false
			],
			[
				[
					InformClientMessageJsonValidator::SOURCE_ID_LABEL => 'abc',
					InformClientMessageJsonValidator::MESSAGE_LABEL => []
				],
				true
			]
		];
	}
}
