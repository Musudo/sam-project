<?php

namespace App\Controller\Api;

use App\Service\AttachmentService;
use App\Service\VoiceMemoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/file', name: 'api_file_')]
class FileController extends AbstractController
{
	public function __construct(private readonly VoiceMemoService  $voiceMemoService,
								private readonly AttachmentService $attachmentService)
	{
	}

	/**
	 * add voice memo to activity
	 */
	#[Route('/voice-memo/{activityId}', name: 'create_voice_memo', methods: ['POST'])]
	public function createVoiceMemo(Request $request, int $activityId): JsonResponse
	{
		$this->voiceMemoService->add($request->files->get('voice_memo'), $activityId);

		return $this->json('Voice memo added', Response::HTTP_OK);
	}

	/**
	 * delete voice memo
	 */
	#[Route('/voice-memo/{activityId}', name: 'remove_voice_memo', methods: ['DELETE'])]
	public function removeVoiceMemo(int $activityId): Response
	{
		$this->voiceMemoService->remove($activityId);

		return $this->json('Voice memo removed', Response::HTTP_OK);
	}

	/**
	 * add attachment to review
	 */
	#[Route('/attachment/{reviewId}', name: 'create_attachment', methods: ['POST'])]
	public function createAttachment(Request $request, int $reviewId): JsonResponse
	{
		$maxFiles = 4;
		$attachments = $request->files;

		if (count($attachments) > $maxFiles) return $this->json('Maximum amount allowed attachments exceeded', Response::HTTP_BAD_REQUEST);

		foreach ($attachments as $attachment) {
			$this->attachmentService->add($attachment, $reviewId);
		}

		return $this->json('Attachment added', Response::HTTP_OK);
	}

	/**
	 * delete attachment
	 */
	#[Route('/attachment/{attachmentId}', name: 'remove_attachment', methods: ['DELETE'])]
	public function removeAttachment(int $attachmentId): Response
	{
		$this->attachmentService->remove($attachmentId);

		return $this->json('Attachment removed', Response::HTTP_OK);
	}
}