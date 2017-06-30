<?php

namespace Controllers\Validators;

class InformClientMessageJsonValidator implements MessageJsonValidatorInterface
{
	const SOURCE_ID_LABEL = 'sourceId';
	const MESSAGE_LABEL = 'message';

	public function validateJson(array $json = null) : bool
	{
		if ($json === null)
		{
			return false;
		}

		if (array_key_exists(self::SOURCE_ID_LABEL, $json) === false ||
			array_key_exists(self::MESSAGE_LABEL, $json) === false)
		{
			return false;
		}

		if (is_string($json[self::SOURCE_ID_LABEL]) === false)
		{
			return false;
		}

		if (is_array($json[self::MESSAGE_LABEL]) === false)
		{
			return false;
		}

		return true;
	}
}
