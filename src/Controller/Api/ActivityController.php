<?php

namespace App\Controller\Api;

use App\Entity\Activity;
use App\Entity\ExternalParticipant;
use App\Form\ActivityType;
use App\Service\ActivityService;
use App\Service\ContactService;
use App\Service\EmailService;
use App\Service\ExternalParticipantService;
use App\Util\ErrorHelper;
use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/activities', name: 'api_activities_')]
class ActivityController extends AbstractController
{
	public function __construct(private readonly ActivityService            $activityService,
								private readonly ContactService             $contactService,
								private readonly EmailService               $emailService,
								private readonly ExternalParticipantService $externalParticipantService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$activities = $this->activityService->findAllForUser();
		if (!$activities) return $this->json('Activities not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/topical', name: 'find_all_topical', methods: ['GET'])]
	public function findAllTopical(): Response
	{
		$activities = $this->activityService->findAllTopicalForUser();
		if (!$activities) return $this->json('Topical activities not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/expired/{year}', name: 'find_all_expired_by_year', methods: ['GET'])]
	public function findAllExpiredByYear(string $year): Response
	{
		$activities = $this->activityService->findAllExpiredByYearForUser(DateTime::createFromFormat('Y-m-d H:i', $year));
		if (!$activities) return $this->json('Expired activities not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/today', name: 'find_topical_for_today', methods: ['GET'])]
	public function findOfToday(): Response
	{
		$activities = $this->activityService->findOfTodayForUser();
		if (!$activities) return $this->json('Activities for today not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/next-seven-days', name: 'find_topical_for_next_seven_days', methods: ['GET'])]
	public function findOfNextSevenDays(): Response
	{
		$activities = $this->activityService->findOfNextSevenDaysForUser();
		if (!$activities) return $this->json('Activities for next seven days not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/next-thirty-days', name: 'find_topical_for_next_thirty_days', methods: ['GET'])]
	public function findOfNextThirtyDays(): Response
	{
		$activities = $this->activityService->findOfNextThirtyDaysForUser();
		if (!$activities) return $this->json('Activities for next thirty days not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/today/institution-info/{param}', name: 'find_topical_for_today_by_institution', methods: ['GET'])]
	public function findOfTodayByInstitution(string $param): Response
	{
		$activities = $this->activityService->findOfTodayByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Activities for today for institution not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/next-seven-days/institution-info/{param}', name: 'find_topical_for_next_seven_days_by_institution', methods: ['GET'])]
	public function findOfNextSevenDaysByInstitution(string $param): Response
	{
		$activities = $this->activityService->findOfNextSevenDaysByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Activities for next seven days for institution not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/next-thirty-days/institution-info/{param}', name: 'find_topical_for_next_thirty_days_by_institution', methods: ['GET'])]
	public function findOfNextThirtyDaysByInstitution(string $param): Response
	{
		$activities = $this->activityService->findOfNextThirtyDaysByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Activities for next thirty days for institution not found', Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('/{guid}', name: 'find_all_topical', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$activity = $this->activityService->findByGuid($guid);
		if (!$activity) return $this->json('Activity not found by guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * find activity by its tag name or type
	 */
	#[Route('/info/{param}', name: 'find_by_type_or_tag', methods: ['GET'])]
	public function findByTypeOrTag(string $param): Response
	{
		$activities = $this->activityService->findAllByTypeOrTagForUser($param);
		if (!$activities) return $this->json('Activities not found by parameter: ' . $param, Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * find all activities by institution guid or name
	 */
	#[Route('/institution-info/{param}', name: 'find_all_by_institution_guid_or_name', methods: ['GET'])]
	public function findByInstitution(string $param): Response
	{
		$activities = $this->activityService->findAllByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Activities not found by institution: ' . $param, Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * find topical activities by institution guid or name
	 */
	#[Route('/institution-info/{param}/topical', name: 'find_all_topical_by_institution_guid_or_name', methods: ['GET'])]
	public function findTopicalByInstitution(string $param): Response
	{
		$activities = $this->activityService->findAllTopicalByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Topical activities not found by institution: ' . $param, Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * find expired activities by institution guid or name
	 */
	#[Route('/institution-info/{param}/expired', name: 'find_all_expired_by_institution_guid_or_name', methods: ['GET'])]
	public function findExpiredByInstitution(string $param): Response
	{
		$activities = $this->activityService->findAllExpiredByInstitutionGuidOrNameForUser($param);
		if (!$activities) return $this->json('Expired activities not found by institution: ' . $param, Response::HTTP_NO_CONTENT);

		return $this->json($activities, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$data = $request->toArray();

		$data['start'] = Carbon::createFromFormat('d-m-Y H:i', $data['start']);
		$data['end'] = Carbon::createFromFormat('d-m-Y H:i', $data['end']);

		if ($data['emailSentAt']) {
			$data['emailSentAt'] = Carbon::createFromFormat('d-m-Y H:i', $data['emailSentAt']);
		} else {
			unset($data['emailSentAt']);
		}

		// 1) create new external participants and save their id's as array
		$extPartsArr = [];
		foreach ($data['externalParticipants'] as $ep) {
			$externalParticipant = new ExternalParticipant();
			$externalParticipant->setEmail($ep['email']);
			$this->externalParticipantService->save($externalParticipant);

			$extPartsArr[] = $externalParticipant->getId();
		}

		unset($data['externalParticipants']);

		// 2) now add the array with those id's to activity object
		if (count($extPartsArr) > 0) $data['externalParticipants'] = $extPartsArr;

		$form = $this->createForm(ActivityType::class);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			/** @var Activity $activity */
			$activity = $form->getData();

			$this->activityService->save($activity);

			// create Microsoft Outlook event
			//$this->outlookEventService->createEvent();

			return $this->json($activity, Response::HTTP_CREATED, [], ['groups' => ['get', 'byActivity']]);
		}

		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	#[Route('/{id}', name: 'update', methods: ['PATCH'])]
	public function update(Request $request, Activity $activity): Response
	{
		$data = $request->toArray();

		// 1) format and prepare changed fields
		$data['start'] = Carbon::createFromFormat('d-m-Y H:i', $data['start']);
		$data['end'] = Carbon::createFromFormat('d-m-Y H:i', $data['end']);
		$data['tags'] = array_column($data['tags'], 'id');

		// 2) add required form fields which are already existing and are not changed at this request
		$data['institution'] = $activity->getInstitution()->getId();
		$data['user'] = $activity->getUser()->getId();
		$data['contacts'] = $activity->getContacts()->map(fn($contact) => $contact->getId())->toArray();

		// 3) remove fields that are not needed for activity form validation
		unset($data['id'], $data['guid'], $data['reports'], $data['tasks'],
			$data['externalParticipants'], $data['voiceMemo'], $data['created'],
			$data['emailSentAt'], $data['review']);

		// now filling form with data
		$form = $this->createForm(ActivityType::class, $activity);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			$this->activityService->save($activity);

			return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
		}

		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	/**
	 * update external note of activity
	 *
	 * @throws InvalidArgumentException
	 */
	#[Route('/{id}/external-note', name: 'update_external_note', methods: ['PATCH'])]
	public function updateExternalNote(Request $request, Activity $activity): Response
	{
		$data = $request->toArray();
		$activity->setExternalNote($data['externalNote']);
		$this->activityService->save($activity);

		return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * update internal note of activity
	 *
	 * @throws InvalidArgumentException
	 */
	#[Route('/{id}/internal-note', name: 'update_internal_note', methods: ['PATCH'])]
	public function updateInternalNote(Request $request, Activity $activity): Response
	{
		$data = $request->toArray();
		$activity->setInternalNote($data['internalNote']);
		$this->activityService->save($activity);

		return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * add one participant to activity
	 *
	 * @throws TransportExceptionInterface
	 * @throws InvalidArgumentException
	 */
	#[Route('/{id}/participant', name: 'add_participant', methods: ['PATCH'])]
	public function addParticipant(Request $request, Activity $activity): Response
	{
		$lang = $request->cookies->get('lang');
		$data = $request->toArray();

		// check if this contact already participates in this activity
		foreach ($activity->getContacts() as $contact) {
			if ($contact->getId() === $data['contact']) {
				return $this->json($contact, Response::HTTP_BAD_REQUEST, [], ['groups' => ['get', 'byActivity']]);
			}
		}

		$contact = $this->contactService->findById($data['contact']);
		$activity->addContact($contact);
		$this->activityService->save($activity);

		// send confirmation email to participant
		$this->emailService->sendActivityConfirmationEmail($activity, $contact->getEmail1(), $contact->getFirstName(), $lang);

		return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * remove participant from activity
	 *
	 * @throws InvalidArgumentException
	 */
	#[Route('/{id}/contact', name: 'remove_contact', methods: ['PATCH'])]
	public function removeParticipant(Request $request, Activity $activity): Response
	{
		$data = $request->toArray();
		$contact = $this->contactService->findById($data['id']);

		$activity->removeContact($contact);
		$this->activityService->save($activity);

		return $this->json($activity, Response::HTTP_OK, [], ['groups' => ['get', 'byActivity']]);
	}

	/**
	 * soft delete of activity
	 */
	#[Route('/{id}', name: 'remove', methods: ['DELETE'])]
	public function remove(Activity $activity): Response
	{
		$this->activityService->remove($activity);

		return $this->json('Activity deleted', Response::HTTP_OK);
	}
}
