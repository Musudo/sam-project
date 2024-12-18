<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tasks', name: 'api_tasks_')]
class TaskController extends AbstractController
{
	public function __construct(private readonly TaskService $taskService)
	{
	}

	#[Route('', name: 'find_all', methods: ['GET'])]
	public function findAll(): Response
	{
		$tasks = $this->taskService->findAllForUser();
		if (!$tasks) return $this->json('Tasks not found', Response::HTTP_NO_CONTENT);

		return $this->json($tasks, Response::HTTP_OK, [], ['groups' => ['get', 'byTask']]);
	}

	#[Route('/activities/{guid}', name: 'find_all_by_activity_guid', methods: ['GET'])]
	public function findAllByActivity(string $guid): Response
	{
		$tasks = $this->taskService->findAllByActivity($guid);
		if (!$tasks) return $this->json('Tasks not found by activity: ' . $guid, Response::HTTP_NO_CONTENT);

		return $this->json($tasks, Response::HTTP_OK, [], ['groups' => ['get', 'byTask']]);
	}

	#[Route('', name: 'create', methods: ['POST'])]
	public function create(Request $request): Response
	{
		$data = $request->toArray();
		$task = $this->taskService->create($data);

		return $this->json($task, Response::HTTP_CREATED, [], ['groups' => ['get', 'byTask']]);
	}

	#[Route('/{id}', name: 'update', methods: ['PATCH'])]
	public function update(Request $request, Task $task): Response
	{
		$data = $request->toArray();
		$task = $this->taskService->update($data, $task);

		return $this->json($task, Response::HTTP_OK, [], ['groups' => ['get', 'byTask']]);
	}

	/**
	 * delete a task form database
	 */
	#[Route('/{id}', name: 'remove', methods: ['DELETE'])]
	public function remove(Task $task): Response
	{
		$this->taskService->remove($task);

		return $this->json("Task removed", Response::HTTP_OK);
	}
}
