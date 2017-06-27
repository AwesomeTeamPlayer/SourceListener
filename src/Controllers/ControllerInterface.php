<?php

namespace Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

interface ControllerInterface
{
	function run(Request $request, Response $response);
}
