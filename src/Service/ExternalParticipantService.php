<?php

namespace App\Service;

use App\Entity\ExternalParticipant;
use App\Exception\ExternalParticipantNotFoundException;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ResourceNotDeletedException;
use App\Repository\ExternalParticipantRepository;
use App\Service\Interface\IExternalParticipantService;
use Exception;

class ExternalParticipantService implements IExternalParticipantService
{
	/**
	 * @param ExternalParticipantRepository $externalParticipantRepository
	 */
	public function __construct(private readonly ExternalParticipantRepository $externalParticipantRepository)
	{
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		try {
			return $this->externalParticipantRepository->findAll();
		} catch (Exception $e) {
			throw new ExternalParticipantNotFoundException("Failed to find external participants");
		}
	}

	/**
	 * @param string $guid
	 * @return ExternalParticipant|null
	 */
	public function findByGuid(string $guid): ?ExternalParticipant
	{
		try {
			return $this->externalParticipantRepository->findOneBy(['guid' => $guid]);
		} catch (Exception $e) {
			throw new ExternalParticipantNotFoundException("Failed to find external participant by guid");
		}
	}

	/**
	 * @param ExternalParticipant $externalParticipant
	 * @return void
	 */
	public function save(ExternalParticipant $externalParticipant): void
	{
		try {
			$this->externalParticipantRepository->save($externalParticipant, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to create external participant");
		}
	}

	/**
	 * @param ExternalParticipant $externalParticipant
	 * @return void
	 */
	public function remove(ExternalParticipant $externalParticipant): void
	{
		try {
			$this->externalParticipantRepository->remove($externalParticipant, true);
		} catch (Exception $e) {
			throw new ResourceNotDeletedException("Failed to delete external participant");
		}
	}
}