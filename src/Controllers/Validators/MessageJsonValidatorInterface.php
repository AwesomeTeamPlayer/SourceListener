<?php

namespace Controllers\Validators;

interface MessageJsonValidatorInterface
{
	public function validateJson(array $json = null) : bool;
}
