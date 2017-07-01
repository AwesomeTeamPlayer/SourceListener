<?php

namespace Domain;

use Domain\Adapters\ClientsSourcesStoreRepositoryInterface;
use Domain\ValueObjects\InformClientsMessage;

class InformClientService
{

	/**
	 * @var ClientsSourcesStoreRepositoryInterface
	 */
	private $clientsSourcesStoreRepository;

	/**
	 * @var int
	 */
	private $paginationLimit;

	/**
	 * @var MessageSender
	 */
	private $messageSender;

	/**
	 * @param ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository
	 * @param int $paginationLimit
	 */
	public function __construct(
		ClientsSourcesStoreRepositoryInterface $clientsSourcesStoreRepository,
		int $paginationLimit
	)
	{
		$this->clientsSourcesStoreRepository = $clientsSourcesStoreRepository;
		$this->paginationLimit = $paginationLimit;
	}

	public function informClient(
		InformClientsMessage $informClientsMessage
	) {
		$totalNumberOfClients = $this->clientsSourcesStoreRepository->countAllClients(
			$informClientsMessage->sourceId()
		);

		$numberOfPages = ceil($totalNumberOfClients / $this->paginationLimit);
		for ($page = 0; $page < $numberOfPages; $page++) {
			$this->informPartOfClients($page, $informClientsMessage);
		}
	}

	private function informPartOfClients(int $page, InformClientsMessage $informClientsMessage)
	{
		$clientsIds = $this->clientsSourcesStoreRepository->getClients(
			$informClientsMessage->sourceId(),
			$page * $this->paginationLimit,
			($page + 1) * $this->paginationLimit
		);

		$this->messageSender->sendMessage(
			$informClientsMessage,
			$clientsIds
		);
	}
}
