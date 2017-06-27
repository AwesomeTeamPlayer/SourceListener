<?php

namespace Controllers\Validators;

class UnregisterClientMessageJsonValidator implements MessageJsonValidatorInterface
{
	const SOURCE_ID_LABEL = 'sourceId';
	const CLIENT_ID_LABEL = 'clientId';

	public function validateJson(array $json = null) : bool
	{
		if ($json === null)
		{
			return false;
		}

		if (array_key_exists(self::CLIENT_ID_LABEL, $json) === false ||
			array_key_exists(self::SOURCE_ID_LABEL, $json) === false)
		{
			return false;
		}

		if (is_string($json[self::CLIENT_ID_LABEL]) === false)
		{
			return false;
		}

		if (is_string($json[self::SOURCE_ID_LABEL]) === false)
		{
			return false;
		}

		return true;
	}
}
