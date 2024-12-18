<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\User;
use App\Exception\ContactNotFoundException;
use App\Exception\ResourceNotCreatedException;
use App\Repository\ContactRepository;
use App\Service\Interface\IContactService;
use Exception;
use Symfony\Component\Security\Core\Security;

class ContactService implements IContactService
{
	private string $guid;

	/**
	 * @param ContactRepository $contactRepository
	 * @param Security $security
	 */
	public function __construct(private readonly ContactRepository $contactRepository,
								private readonly Security          $security)
	{
		$this->guid = $this->security->getUser()->getUserIdentifier();
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		// because of too much data and that admin has no own contacts, we are getting only first 200 records for now
		try {
			return $this->contactRepository->findBy([], null, 200, 0);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByContactInfoForAdmin(string $param): array
	{
		try {
			return $this->contactRepository->findAllByContactInfoForAdmin($param);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contact");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByInstitutionGuidOrNameForAdmin(string $param): array
	{
		try {
			return $this->contactRepository->findAllByInstitutionGuidOrNameForAdmin($param);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts");
		}
	}

	/**
	 * @return float|int|mixed|string
	 */
	public function findAllForUser(): mixed
	{
		try {
			return $this->contactRepository->findAllForUser($this->guid);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByContactInfoForUser(string $param): array
	{
		try {
			return $this->contactRepository->findAllByContactInfoForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts for user");
		}
	}

	/**
	 * @param string $param
	 * @return array
	 */
	public function findAllByInstitutionGuidOrNameForUser(string $param): array
	{
		try {
			return $this->contactRepository->findAllByInstitutionGuidOrNameForUser($param, $this->guid);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts for user");
		}
	}

	/**
	 * @param string $guid
	 * @return array
	 */
	public function findInstitutionsOfContact(string $guid): array
	{
		try {
			return $this->contactRepository->findInstitutionsOfContact($guid);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find institutions for contact");
		}
	}

	/**
	 * @param string $guid
	 * @return array
	 */
	public function findAllByActivity(string $guid): array
	{
		try {
			return $this->contactRepository->findAllByActivity($guid);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contacts for activity");
		}
	}

	/**
	 * @param string $guid
	 * @return Contact|null
	 */
	public function findByGuid(string $guid): ?Contact
	{
		try {
			return $this->contactRepository->findOneBy(['guid' => $guid]);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contact by guid");
		}
	}

	/**
	 * @param int $id
	 * @return Contact|null
	 */
	public function findById(int $id): ?Contact
	{
		try {
			return $this->contactRepository->find($id);
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contact by id");
		}
	}

	/**
	 * @param string $email
	 * @return User|null
	 */
	public function findByEmail(string $email): ?Contact
	{
		try {
			return $this->contactRepository->findOneBy(['email1' => $email]);;
		} catch (Exception $e) {
			throw new ContactNotFoundException("Failed to find contact by email");
		}
	}

	/**
	 * @param Contact $contact
	 * @return void
	 */
	public function save(Contact $contact): void
	{
		try {
			$this->contactRepository->save($contact, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to create new contact");
		}
	}

	/**
	 * @param Contact $contact
	 * @return void
	 */
	public function remove(Contact $contact): void
	{
		try {
			$this->contactRepository->remove($contact, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to delete contact");
		}
	}
}