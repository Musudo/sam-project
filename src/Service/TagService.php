<?php

namespace App\Service;

use App\Exception\TagNotFoundException;
use App\Repository\TagRepository;
use App\Service\Interface\ITagService;
use Exception;

class TagService implements ITagService
{
	/**
	 * @param TagRepository $tagRepository
	 */
	public function __construct(private readonly TagRepository $tagRepository)
	{
	}

	/**
	 * @return array
	 */
	public function findAll(): array
	{
		try {
			return $this->tagRepository->findAll();
		} catch (Exception $e) {
			throw new TagNotFoundException("Failed to find tags");
		}
	}
}