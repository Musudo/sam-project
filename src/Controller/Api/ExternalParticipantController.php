<?php

namespace App\Controller\Api;

use App\Entity\ExternalParticipant;
use App\Form\ExternalParticipantType;
use App\Service\ActivityService;
use App\Service\EmailService;
use App\Service\ExternalParticipantService;
use App\Util\ErrorHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/external-participants', name: 'app_external_participants_')]
class ExternalParticipantController extends AbstractController
{
	public function __construct(private readonly ExternalParticipantService $externalParticipantService,
								private readonly ActivityService            $activityService,
								private readonly EmailService               $emailService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$externalParticipants = $this->externalParticipantService->findAllForAdmin();
		if (!$externalParticipants) return $this->json('External participants not found', Response::HTTP_NO_CONTENT);

		return $this->json($externalParticipants, Response::HTTP_OK, [], ['groups' => ['get', 'byExternalParticipant']]);
	}

	#[Route('/{guid}', name: 'find_by_guid', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$externalParticipant = $this->externalParticipantService->findByGuid($guid);
		if (!$externalParticipant) return $this->json('External participant not found by guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($externalParticipant, Response::HTTP_OK, [], ['groups' => ['get', 'byExternalParticipant']]);
	}

	/**
	 * add new external participant to activity and save him to database
	 * @throws TransportExceptionInterface
	 */
	#[Route('/activity/{activityId}', name: 'create', methods: ['POST'])]
	public function create(Request $request, string $activityId): Response
	{
		$lang = $request->cookies->get('lang');
		$data = $request->toArray();
		$activity = $this->activityService->findById($activityId);

		$form = $this->createForm(ExternalParticipantType::class);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			/** @var ExternalParticipant $externalParticipant */
			$externalParticipant = $form->getData();

			$activity->addExternalParticipant($externalParticipant);
			$this->activityService->save($activity);

			/* send confirmation email to external participant */
			$this->emailService->sendActivityConfirmationEmail($activity, $externalParticipant->getEmail(),
				'', $lang);

			return $this->json('External participant added', Response::HTTP_OK, [], ['groups' => ['get', 'byExternalParticipant']]);
		} else {
			return $this->json($errors, Response::HTTP_BAD_REQUEST);
		}
	}

	/**
	 * remove external participant from database
	 */
	#[Route('/{id}', name: 'remove', methods: ['DELETE'])]
	public function remove(ExternalParticipant $externalParticipant): Response
	{
		$this->externalParticipantService->remove($externalParticipant);

		return $this->json("External participant removed", Response::HTTP_OK);
	}
}
