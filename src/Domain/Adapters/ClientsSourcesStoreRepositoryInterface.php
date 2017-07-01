<?php

namespace Domain\Adapters;

/**
 * Interface for store storing unique (clientId, sourceId) pairs.
 */
interface ClientsSourcesStoreRepositoryInterface
{
	/**
	 * Just add (clientId, sourceId) pair to the store.
	 *
	 * @param string $clientId
	 * @param string $sourceId
	 *
	 * @return void
	 */
	function add(string $clientId, string $sourceId);

	/**
	 * This method removes (clientId, sourceId) pair.
	 * If this pair did not exist this method do nothing.
	 *
	 * @param string $clientId
	 * @param string $sourceId
	 *
	 * @return void
	 */
	function remove(string $clientId, string $sourceId);

	/**
	 * This method count all clients connected with a specific source ID.
	 *
	 * @param string $sourceId
	 *
	 * @return int
	 */
	function countAllClients(string $sourceId) : int;

	/**
	 * This method returns clients' IDs connected to a specific source ID.
	 *
	 * @param string $sourceId
	 * @param int $offset
	 * @param int $limit
	 *
	 * @return string[]
	 */
	function getClients(string $sourceId, int $offset, int $limit) : array;
}
