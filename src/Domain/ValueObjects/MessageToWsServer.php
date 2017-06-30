<?php

namespace Domain\ValueObjects;

class MessageToWsServer
{
	/**
	 * @var string[]
	 */
	private $connectionsIds;

	/**
	 * @var string
	 */
	private $sourceId;

	/**
	 * @var array
	 */
	private $message;

	/**
	 * @param string[] $connectionsIds
	 * @param string $sourceId
	 * @param array $message
	 */
	public function __construct(
		array $connectionsIds,
		string $sourceId,
		array $message
	)
	{
		$this->connectionsIds = $connectionsIds;
		$this->sourceId = $sourceId;
		$this->message = $message;
	}

	/**
	 * @return string[]
	 */
	public function connectionsIds(): array
	{
		return $this->connectionsIds;
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
