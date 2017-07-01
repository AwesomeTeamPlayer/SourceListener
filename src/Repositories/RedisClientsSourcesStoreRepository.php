<?php

namespace Repositories;

use Domain\Adapters\ClientsSourcesStoreRepositoryInterface;
use Redis;

class RedisClientsSourcesStoreRepository implements ClientsSourcesStoreRepositoryInterface
{
	/**
	 * @var Redis
	 */
	private $redis;

	public function __construct(string $host, int $post)
	{
//		$this->redis = $redis;
	}

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
		$this->redis->lPush($sourceId, $clientId);
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
		$this->redis->lRem($sourceId, $clientId, 0);
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
		return (int) $this->redis->lLen($sourceId);
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
		return $this->redis->lRange($sourceId, $offset, $offset + $limit);
	}
}
