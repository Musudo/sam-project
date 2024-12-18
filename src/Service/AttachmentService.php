<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ResourceNotDeletedException;
use App\Exception\ReviewNotFoundException;
use App\Repository\AttachmentRepository;
use App\Service\Interface\IAttachmentService;
use Exception;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class AttachmentService implements IAttachmentService
{
	/**
	 * @param AttachmentRepository $attachmentRepository
	 * @param ReviewService $reviewService
	 * @param FileService $fileUploadService
	 */
	public function __construct(private readonly AttachmentRepository $attachmentRepository,
								private readonly ReviewService        $reviewService,
								private readonly FileService          $fileUploadService)
	{
	}

	/**
	 * @param int $id
	 * @return Attachment|null
	 */
	public function findById(int $id): ?Attachment
	{
		try {
			return $this->attachmentRepository->find($id);
		} catch (Exception $e) {
			throw new ReviewNotFoundException("Failed to find attachment by id");
		}
	}

	/**
	 * link an attachment to review and save it afterwards
	 *
	 * @param $attachment
	 * @param int $reviewId
	 * @return Attachment
	 */
	public function add($attachment, int $reviewId): Attachment
	{
		try {
			$filepath = $this->fileUploadService->uploadAttachment($attachment);

			// add attachment path to database
			$review = $this->reviewService->findById($reviewId);
			$attachment = new Attachment();
			$attachment->setPath($filepath);
			$attachment->setReview($review);
			$this->attachmentRepository->save($attachment, true);

			return $attachment;
		} catch (IOExceptionInterface $e) {
			throw new ResourceNotCreatedException("Failed to add attachment");
		}
	}

	/**
	 * just save an attachment to database -> should be used only in very specific cases or inside add() function
	 *
	 * @param Attachment $attachment
	 * @return void
	 */
	public function save(Attachment $attachment): void
	{
		try {
			$this->attachmentRepository->save($attachment, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to save attachment");
		}
	}

	/**
	 * @param int $id
	 * @return void
	 */
	public function remove(int $id): void
	{
		try {
			$attachment = $this->attachmentRepository->find($id);
			$filepath = $attachment->getPath();

			$this->fileUploadService->removeFile($filepath);
			$this->attachmentRepository->remove($attachment, true);
		} catch (Exception $e) {
			throw new ResourceNotDeletedException("Failed to delete attachment");
		}
	}
}