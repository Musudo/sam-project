<?php

namespace App\Controller\Api;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users', name: 'api_users')]
class UserController extends AbstractController
{

	public function __construct(private readonly UserService $userService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$users = $this->userService->findAll();
		if (!$users) return $this->json('Users not found', Response::HTTP_NO_CONTENT);

		return $this->json($users, Response::HTTP_OK, [], ['groups' => ['get', 'byUser']]);
	}

	#[Route('/{guid}', name: 'find_by_guid', methods: ['GET'])]
	public function findByGuid(string $guid): Response
	{
		$user = $this->userService->findByGuid($guid);
		if (!$user) return $this->json('User not found by guid: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($user, Response::HTTP_OK, [], ['groups' => ['get', 'byUser']]);
	}
}
