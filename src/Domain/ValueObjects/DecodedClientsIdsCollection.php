<?php

namespace Domain\ValueObjects;

class DecodedClientsIdsCollection
{
	/**
	 * @var DecodedClientId[]
	 */
	private $decodedClientsIds;

	/**
	 * @param DecodedClientId[] $decodedClientsIds
	 */
	public function __construct(array $decodedClientsIds)
	{
		$this->decodedClientsIds = $decodedClientsIds;
	}

	/**
	 * @return DecodedClientId[]
	 */
	public function toArray() : array
	{
		return $this->decodedClientsIds;
	}

	/**
	 * @return DecodedClientsIdsCollection
	 */
	public function sortCollection(): DecodedClientsIdsCollection
	{
		$cloneDecodedClientsIds = $this->cloneArray($this->decodedClientsIds);

		usort(
			$cloneDecodedClientsIds,
			function(DecodedClientId $a, DecodedClientId $b)
			{
				if ($a->uri()->__toString() === $b->uri()->__toString())
				{
					return 0;
				}

				if ($a->uri()->__toString() < $b->uri()->__toString())
				{
					return -1;
				}

				return 1;
			}
		);

		return new DecodedClientsIdsCollection($cloneDecodedClientsIds);
	}

	/**
	 * @param array $array
	 *
	 * @return array
	 */
	private function cloneArray(array $array) : array
	{
		$clonedArray = [];

		foreach ($array as $item) {
			$clonedArray[] = $item;
		}

		return $clonedArray;
	}

	/**
	 * @return DecodedClientId[][]
	 */
	public function group(): array
	{
		$grouped = [];

		foreach ($this->decodedClientsIds as $decodedClientId)
		{
			$grouped[$decodedClientId->uri()->__toString()][] = $decodedClientId;
		}

		return $grouped;
	}

}
