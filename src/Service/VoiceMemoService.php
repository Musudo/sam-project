<?php

namespace App\Service;

use App\Entity\VoiceMemo;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ResourceNotDeletedException;
use App\Repository\VoiceMemoRepository;
use App\Service\Interface\IVoiceMemoService;
use Exception;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class VoiceMemoService implements IVoiceMemoService
{
	/**
	 * @param VoiceMemoRepository $voiceMemoRepository
	 * @param ActivityService $activityService
	 * @param FileService $fileUploadService
	 */
	public function __construct(private readonly VoiceMemoRepository $voiceMemoRepository,
								private readonly ActivityService     $activityService,
								private readonly FileService         $fileUploadService)
	{
	}

	/**
	 * link a voice memo to activity and save it afterwards
	 *
	 * @param $audio
	 * @param int $activityId
	 * @return VoiceMemo
	 */
	public function add($audio, int $activityId): VoiceMemo
	{
		try {
			$filepath = $this->fileUploadService->uploadAudio($audio);

			// add voice memo path to database
			$activity = $this->activityService->findById($activityId);
			$voiceMemo = new VoiceMemo();
			$voiceMemo->setPath($filepath);
			$voiceMemo->setActivity($activity);
			$this->voiceMemoRepository->save($voiceMemo, true);

			return $voiceMemo;
		} catch (IOExceptionInterface $e) {
			throw new ResourceNotCreatedException("Failed to add voice memo");
		}
	}

	/**
	 * just save a voice memo to database -> should be used only in very specific cases
	 *
	 * @param VoiceMemo $voiceMemo
	 * @return void
	 */
	public function save(VoiceMemo $voiceMemo): void
	{
		try {
			$this->voiceMemoRepository->save($voiceMemo, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to save voice memo");
		}
	}

	/**
	 * remove a voice memo from uploads directory and database
	 *
	 * @param int $activityId
	 * @return void
	 */
	public function remove(int $activityId): void
	{
		try {
			$voiceMemo = $this->activityService->findById($activityId)->getVoiceMemo();
			$filepath = $voiceMemo->getPath();

			$this->fileUploadService->removeFile($filepath);
			$this->voiceMemoRepository->remove($voiceMemo, true);
		} catch (IOExceptionInterface $e) {
			throw new ResourceNotDeletedException("Failed to delete voice memo");
		}
	}
}