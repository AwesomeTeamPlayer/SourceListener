<?php

namespace Domain\ValueObjects;

use Psr\Http\Message\UriInterface;

class DecodedClientId
{
	/**
	 * @var UriInterface
	 */
	private $uri;

	/**
	 * @var string
	 */
	private $connectionId;

	/**
	 * @param UriInterface $uri
	 * @param string $connectionId
	 */
	public function __construct(
		UriInterface $uri,
		string $connectionId
	)
	{
		$this->uri = $uri;
		$this->connectionId = $connectionId;
	}

	/**
	 * @return UriInterface
	 */
	public function uri(): UriInterface
	{
		return $this->uri;
	}

	/**
	 * @return string
	 */
	public function connectionId(): string
	{
		return $this->connectionId;
	}

}
