<?php

namespace tests\helpers;

use Domain\Adapters\ClientsSourcesStoreRepositoryInterface;

class MemoryClientsSourcesStoreRepository implements ClientsSourcesStoreRepositoryInterface
{

	/**
	 * First index is source ID, inside array contains client IDs.
	 *
	 * @var string[][]
	 */
	private $clients = [];

	/**
	 * Just add (clientId, sourceId) pair to the store.
	 *
	 * @param string $clientId
	 * @param string $sourceId
	 *
	 * @return void
	 */
	public function add(string $clientId, string $sourceId)
	{
		$this->clients[$sourceId][] = $clientId;
	}

	/**
	 * This method removes (clientId, sourceId) pair.
	 * If this pair did not exist this method do nothing.
	 *
	 * @param string $clientId
	 * @param string $sourceId
	 *
	 * @return void
	 */
	public function remove(string $clientId, string $sourceId)
	{
		if (array_key_exists($sourceId, $this->clients) === false)
		{
			return;
		}

		$this->clients[$sourceId] = array_diff($this->clients[$sourceId], [ $clientId ]);
	}

	/**
	 * This method count all clients connected with a specific source ID.
	 *
	 * @param string $sourceId
	 *
	 * @return int
	 */
	public function countAllClients(string $sourceId): int
	{
		if (array_key_exists($sourceId, $this->clients) === false)
		{
			return 0;
		}

		return count($this->clients[$sourceId]);
	}

	/**
	 * This method returns clients' IDs connected to a specific source ID.
	 *
	 * @param string $sourceId
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return string[]
	 */
	public function getClients(string $sourceId, int $offset, int $limit): array
	{
		if (array_key_exists($sourceId, $this->clients) === false)
		{
			return [];
		}

		$toReturn = [];
		for ($i = $offset; $i < min($offset + $limit, count($this->clients[$sourceId])); $i++)
		{
			$toReturn[] = $this->clients[$sourceId][$i];
		}
		return $toReturn;
	}

	/**
	 * @return string[][]
	 */
	public function dump() : array
	{
		return $this->clients;
	}
}
