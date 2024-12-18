<?php

namespace App\Controller\Api;

use App\Service\TagService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tags', name: 'api_tags_')]
class TagController extends AbstractController
{
	public function __construct(private readonly TagService $tagService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$tags = $this->tagService->findAll();
		if (!$tags) return $this->json('Tags not found', Response::HTTP_NO_CONTENT);

		return $this->json($tags, Response::HTTP_OK, [], ['groups' => ['get']]);
	}
}
