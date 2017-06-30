<?php

namespace Domain\ValueObjects;

use Domain\ValueObjects\Exceptions\IncorrectDecodedClientIdObjectException;

class DecodedClientsIdsWithUniqueUriCollection implements \Iterator
{
	/**
	 * @var int
	 */
	private $position = 0;

	/**
	 * @var DecodedClientId[]
	 */
	private $array = [];

	/**
	 * @var int
	 */
	private $size = 0;

	/**
	 * @param DecodedClientId $decodedClientId
	 *
	 * @return void
	 *
	 * @throws IncorrectDecodedClientIdObjectException
	 */
	public function push(DecodedClientId $decodedClientId)
	{
		if (count($this->array) > 0 &&
			$this->array[0]->uri()->__toString() !== $decodedClientId->uri()->__toString()) {
			throw new IncorrectDecodedClientIdObjectException();
		}

		$this->array[] = $decodedClientId;
		$this->size++;
	}

	/**
	 * @return int
	 */
	public function size() : int
	{
		return $this->size;
	}

	/**
	 * @return void
	 */
	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * @return DecodedClientId
	 */
	public function current() : DecodedClientId
	{
		return $this->array[$this->position];
	}

	/**
	 * @return int
	 */
	public function key() : int
	{
		return $this->position;
	}

	/**
	 * @return void
	 */
	public function next()
	{
		++$this->position;
	}

	/**
	 * @return bool
	 */
	public function valid() : bool
	{
		return isset($this->array[$this->position]);
	}
}
