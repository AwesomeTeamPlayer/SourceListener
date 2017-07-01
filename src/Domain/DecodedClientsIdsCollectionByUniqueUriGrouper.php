<?php

namespace Domain;

use Domain\ValueObjects\DecodedClientId;
use Domain\ValueObjects\DecodedClientsIdsCollection;
use Domain\ValueObjects\DecodedClientsIdsWithUniqueUriCollection;
use Domain\ValueObjects\Exceptions\IncorrectDecodedClientIdObjectException;

class DecodedClientsIdsCollectionByUniqueUriGrouper
{
	/**
	 * @param DecodedClientsIdsCollection $decodedClientsIdsCollection
	 *
	 * @return DecodedClientsIdsWithUniqueUriCollection[]
	 */
	public function group(DecodedClientsIdsCollection $decodedClientsIdsCollection) : array
	{
		$grouped = [];

		foreach ($decodedClientsIdsCollection->toArray() as $decodedClientId)
		{
			$uriAsString = $decodedClientId->uri()->__toString();

			if (array_key_exists($uriAsString, $grouped))
			{
				$this->tryPushToGroupedCollection(
					$grouped[$uriAsString],
					$decodedClientId
				);
			}
			else
			{
				$grouped[$uriAsString] = new DecodedClientsIdsWithUniqueUriCollection(
					[
						$decodedClientId
					]
				);
			}
		}

		return $grouped;
	}

	/**
	 * @param DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection
	 * @param DecodedClientId $decodedClientId
	 *
	 * @return void
	 */
	private function tryPushToGroupedCollection(
		DecodedClientsIdsWithUniqueUriCollection $decodedClientsIdsWithUniqueUriCollection,
		DecodedClientId $decodedClientId
	)
	{
		try {
			$decodedClientsIdsWithUniqueUriCollection->push($decodedClientId);
		}
		catch (IncorrectDecodedClientIdObjectException $exception)
		{
		}
	}
}
