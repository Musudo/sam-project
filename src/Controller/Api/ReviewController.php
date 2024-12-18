<?php

namespace App\Controller\Api;

use App\Entity\Review;
use App\Form\ReviewType;
use App\Service\AttachmentService;
use App\Service\ReviewService;
use App\Util\ErrorHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/reviews', name: 'api_reviews_')]
class ReviewController extends AbstractController
{
	public function __construct(private readonly ReviewService     $reviewService,
								private readonly AttachmentService $attachmentService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$reviews = $this->reviewService->findAllForAdmin();
		if (!$reviews) return $this->json('Reviews not found', Response::HTTP_NO_CONTENT);

		return $this->json($reviews, Response::HTTP_OK, [], ['groups' => ['get', 'byReview']]);
	}

	#[Route('/{guid}', name: 'find_by_guid', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$review = $this->reviewService->findByGuid($guid);
		if (!$review) return $this->json('Review not found by guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($review, Response::HTTP_OK, [], ['groups' => ['get', 'byReview']]);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$data = $request->toArray();

		$form = $this->createForm(ReviewType::class);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			/** @var Review $review */
			$review = $form->getData();
			$this->reviewService->save($review);

			return $this->json($review, Response::HTTP_OK, [], ['groups' => ['get', 'byReview']]);
		}
		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	#[Route('/{id}', name: 'update', methods: ['PATCH'])]
	public function update(Request $request, Review $review): Response
	{
		$data = $request->toArray();

		$form = $this->createForm(ReviewType::class, $review);
		$form->submit($data);

		$errors = ErrorHelper::getErrorMessagesArray($form->getErrors(true));

		if ($form->isSubmitted() && $form->isValid() && count($errors) <= 0) {
			$this->reviewService->save($review);

			return $this->json($review, Response::HTTP_OK, [], ['groups' => ['get', 'byReview']]);
		}
		return $this->json($errors, Response::HTTP_BAD_REQUEST);
	}

	#[Route('/attachment/{id}', name: 'remove_attachment', methods: ['DELETE'])]
	public function removeAttachment(int $id): Response
	{
		$this->attachmentService->remove($id);

		return $this->json('Attachment deleted', Response::HTTP_OK);
	}

	/**
	 * @throws TransportExceptionInterface
	 */
	/*#[Route('/{id}', name: 'send', methods: ['POST'])]
	public function send(Request $request, Review $review): JsonResponse
	{
		$data = $request->toArray();
		$userFullName = $this->userService->findByGuid($this->security->getUser()->getUserIdentifier())->getFullName();

		foreach ($data['recipients'] as $recipientEmail) {
			$contact = $this->contactService->findByEmail($recipientEmail);
			// send review email to each recipient
			$this->emailService->sendReviewEmail($review, $contact, $userFullName);
		}

		$email = new Email();
		$email->setReview($review);
		foreach ($data['recipients'] as $recipient) {
			$email->setEmail($recipient);
		}

		$this->emailEntityService->save($email);

		return $this->json($email, Response::HTTP_OK, [], ['groups' => ['get', 'byReview']]);
	}*/
}
