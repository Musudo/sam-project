<?php

namespace App\Controller\Api;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Service\ContactService;
use App\Service\InstitutionService;
use App\Util\ErrorHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/contacts', name: 'api_contacts_')]
class ContactController extends AbstractController
{
	public function __construct(private readonly ContactService     $contactService,
								private readonly InstitutionService $institutionService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$contacts = $this->contactService->findAllForUser();
		if (!$contacts) return $this->json('Contacts not found', Response::HTTP_NO_CONTENT);

		return $this->json($contacts, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	#[Route('/{guid}', name: 'find_by_guid', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$contact = $this->contactService->findByGuid($guid);
		if (!$contact) return $this->json('Contact not found for guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($contact, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	/**
	 * find all contacts of a specific user by guid or name of institution
	 */
	#[Route('/institution-guid-name/{param}', name: 'find_all_by_institution_guid_or_name', methods: ['GET'])]
	public function findAllByInstitutionGuidOrName(string $param): Response
	{
		$contacts = $this->contactService->findAllByInstitutionGuidOrNameForUser($param);
		if (!$contacts) return $this->json('Contacts not found', Response::HTTP_NO_CONTENT);

		return $this->json($contacts, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	/**
	 * find all contacts of a specific user by firstName, lastName, full name, email1, email2 or jobTitle of contact
	 */
	#[Route('/info/{param}', name: 'find_all_by_contact_info', methods: ['GET'])]
	public function findAllByContactInfo(string $param): Response
	{
		$param .= '%';

		$contacts = $this->contactService->findAllByContactInfoForUser($param);
		if (!$contacts) return $this->json('Contact not found', Response::HTTP_NO_CONTENT);

		return $this->json($contacts, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	#[Route('/{guid}/institutions', name: 'find_institutions_of_contact', methods: ['GET'])]
	public function findInstitutions(string $guid): Response
	{
		$contact = $this->contactService->findInstitutionsOfContact($guid);
		if (!$contact) return $this->json('Institutions of this contact not found', Response::HTTP_NO_CONTENT);

		return $this->json($contact, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	#[Route('/activities/{guid}', name: 'find_by_activity_guid', methods: ['GET'])]
	public function findByActivity(string $guid): Response
	{
		$contacts = $this->contactService->findAllByActivity($guid);
		if (!$contacts) return $this->json('Contact not found', Response::HTTP_NO_CONTENT);

		return $this->json($contacts, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$data = $request->toArray();

		// we will need this array to properly pass institution ID to contact entity
		$institutions = $data['institutions'];

		unset($data['user'], $data['institutions']);

		$form = $this->createForm(ContactType::class);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			/** @var Contact $contact */
			$contact = $form->getData();

			foreach ($institutions as $institution) {
				$this->institutionService->findById($institution['id'])->addContact($contact);
			}
			$this->contactService->save($contact);

			return $this->json($contact, Response::HTTP_CREATED, [], ['groups' => ['get', 'byContact']]);
		}

		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	#[Route('/{id}', name: 'update', methods: ['PATCH'])]
	public function update(Request $request, Contact $contact): Response
	{
		$data = $request->toArray();

		$activities = [];
		foreach ($data['activities'] as $activity) {
			$activities[] = $activity['id'];
		}

		$institutions = [];
		foreach ($data['institutions'] as $institution) {
			$institutions[] = $institution['id'];
		}

		unset($data['id'], $data['guid'], $data['institutions'], $data['activities'], $data['created']);

		if (!empty($activities)) $data['activities'] = $activities;

		$form = $this->createForm(ContactType::class, $contact);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {

			foreach ($institutions as $institution) {
				$this->institutionService->findById($institution)->addContact($contact);
			}

			$this->contactService->save($contact);

			return $this->json($contact, Response::HTTP_OK, [], ['groups' => ['get', 'byContact']]);
		}

		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	#[Route('/{id}', name: 'remove', methods: ['DELETE'])]
	public function remove(Contact $contact): Response
	{
		$this->contactService->remove($contact);

		return $this->json("Contact removed", Response::HTTP_OK);
	}
}
