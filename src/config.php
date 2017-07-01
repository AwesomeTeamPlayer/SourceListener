<?php

return [
	'redis' => [
		'host' => getenv('REDIS_HOST'),
		'port' => getenv('REDIS_PORT')
	],
	'pagination_limit' => getenv('PAGINATION_LIMIT')
];
