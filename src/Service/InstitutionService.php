<?php

namespace App\Service;

use App\Entity\Institution;
use App\Exception\InstitutionNotFoundException;
use App\Repository\InstitutionRepository;
use App\Service\Interface\IInstitutionService;
use Exception;
use Symfony\Component\Security\Core\Security;

class InstitutionService implements IInstitutionService
{
	private string $guid;

	/**
	 * @param InstitutionRepository $institutionRepository
	 * @param Security $security
	 */
	public function __construct(private readonly InstitutionRepository $institutionRepository,
								private readonly Security              $security)
	{
		$this->guid = $this->security->getUser()->getUserIdentifier();
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		try {
			// because of too much data and that admin has no own institutions, we are getting only first 200 records for now
			return $this->institutionRepository->findBy([], null, 200, 0);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institutions");
		}
	}

	/**
	 * @param string $param name, clientId, country, zipCode or city
	 * @return Institution[]|float|int|mixed|string
	 */
	public function findByInfoForAdmin(string $param): mixed
	{
		try {
			return $this->institutionRepository->findByInfoForAdmin($param);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institution");
		}
	}

	/**
	 * @return array
	 */
	public function findAllForUser(): array
	{
		try {
			return $this->institutionRepository->findAllForUser($this->guid);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institutions for user");
		}
	}

	/**
	 * @param string $param name, clientId, country, zipCode or city
	 * @return array
	 */
	public function findByInfoForUser(string $param): array
	{
		try {
			return $this->institutionRepository->findByInfoForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institution for user");
		}
	}

	/**
	 * @param string $guid
	 * @return Institution|null
	 */
	public function findByGuid(string $guid): ?Institution
	{
		try {
			return $this->institutionRepository->findOneBy(['guid' => $guid]);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institution by guid");
		}
	}

	/**
	 * @param int $id
	 * @return Institution|null
	 */
	public function findById(int $id): ?Institution
	{
		try {
			return $this->institutionRepository->find($id);
		} catch (Exception $e) {
			throw new InstitutionNotFoundException("Failed to find institution by id");
		}
	}
}