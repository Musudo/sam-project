<?php

namespace App\Controller\Api;

use App\Entity\Activity;
use App\Entity\Email;
use App\Entity\Review;
use App\Service\ActivityService;
use App\Service\ContactService;
use App\Service\EmailEntityService;
use App\Service\EmailService;
use App\Service\UserService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/api/email', name: 'api_email_')]
class EmailController extends AbstractController
{
	/**
	 * @param EmailService $emailService
	 * @param UserService $userService
	 * @param Security $security
	 * @param ContactService $contactService
	 * @param ActivityService $activityService
	 * @param EmailEntityService $emailEntityService
	 */
	public function __construct(private readonly EmailService       $emailService,
								private readonly UserService        $userService,
								private readonly Security           $security,
								private readonly ContactService     $contactService,
								private readonly ActivityService    $activityService,
								private readonly EmailEntityService $emailEntityService)
	{
	}

	/**
	 * send email with activity confirmation
	 *
	 * @throws TransportExceptionInterface
	 */
	#[Route('/activity/{id}/confirm', name: 'send_activity_confirmation', methods: ['POST'])]
	public function sendActivityConfirmation(Request $request, Activity $activity): Response
	{
		$lang = $request->cookies->get('lang');

		// send confirmation email to all contacts that participate in this activity
		foreach ($activity->getContacts() as $contact) {
			$this->emailService->sendActivityConfirmationEmail($activity, $contact->getEmail1(),
				$contact->getFirstName(), $lang);
		}

		// send confirmation email to all externals that participate in this activity
		foreach ($activity->getExternalParticipants() as $ep) {
			$this->emailService->sendActivityConfirmationEmail($activity, $ep->getEmail(), "sir/madame", $lang);
		}

		if (empty($activity->getEmailSentAt())) {
			$activity->setEmailSentAt(Carbon::now());
			$this->activityService->save($activity);
		}

		return $this->json("Email sent", Response::HTTP_OK);
	}

	/**
	 * send email with activity cancellation
	 *
	 * @throws TransportExceptionInterface
	 */
	#[Route('/activity/cancel', name: 'send_activity_cancellation', methods: ['POST'])]
	public function sendActivityCancellation(Request $request): Response
	{
		$lang = $request->cookies->get('lang');
		$data = $request->toArray()['activity'];
		$userFullName = $data['user']['firstName'] . ' ' . $data['user']['lastName'];
		$date = date('d-m-Y H:i', strtotime($data['start']));
		$subject = $data['subject'];
		$location = $data['institution']['city'] . ' ' . $data['institution']['zipCode'] . ', '
			. $data['institution']['street'] . ' ' . $data['institution']['houseNumber'];

		// send cancellation email to all contacts that participate in this activity
		foreach ($data['contacts'] as $contact) {
			$this->emailService->sendActivityCancellationEmail($userFullName, $subject, $location, $date, $contact['email1'],
				$contact['firstName'], $lang);
		}

		// send cancellation email to all contacts that participate in this activity
		foreach ($data['externalParticipants'] as $ep) {
			$this->emailService->sendActivityCancellationEmail($userFullName, $subject, $location, $date, $ep['email'],
				"sir/madame", $lang);
		}

		return $this->json("Email sent", Response::HTTP_OK);
	}

	/**
	 * send email with review
	 *
	 * @throws TransportExceptionInterface
	 */
	#[Route('/review/{id}', name: 'send_review', methods: ['POST'])]
	public function sendReview(Request $request, Review $review): JsonResponse
	{
		$lang = $request->cookies->get('lang');
		$data = $request->toArray();
		$userFullName = $this->userService->findByGuid($this->security->getUser()->getUserIdentifier())->getFullName();

		// send review email to each recipient
		foreach ($data['recipients'] as $recipientEmail) {
			$contact = $this->contactService->findByEmail($recipientEmail);
			$this->emailService->sendReviewEmail($review, $contact, $userFullName, $lang);
		}

		// persist the email to database after sending it
		foreach ($data['recipients'] as $recipient) {
			$email = new Email();
			$email->setReview($review);
			$email->setEmail($recipient);

			$this->emailEntityService->save($email);
		}

		return $this->json('Email sent', Response::HTTP_OK);
	}
}
