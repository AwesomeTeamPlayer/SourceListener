<?php

namespace Domain\ValueObjects;

class InformClientsMessage
{
	/**
	 * @var string
	 */
	private $sourceId;

	/**
	 * @var array
	 */
	private $message;

	/**
	 * @param string $sourceId
	 * @param array $message
	 */
	public function __construct(
		string $sourceId,
		array $message
	)
	{
		$this->sourceId = $sourceId;
		$this->message = $message;
	}

	/**
	 * @return string
	 */
	public function sourceId(): string
	{
		return $this->sourceId;
	}

	/**
	 * @return array
	 */
	public function message(): array
	{
		return $this->message;
	}
}
