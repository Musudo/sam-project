<?php

namespace App\Service;

use App\Entity\Task;
use App\Exception\ResourceNotCreatedException;
use App\Exception\ResourceNotDeletedException;
use App\Exception\ResourceNotUpdatedException;
use App\Exception\TaskNotFoundException;
use App\Repository\TaskRepository;
use App\Service\Interface\ITaskService;
use Doctrine\Common\Collections\Collection;
use Exception;
use Symfony\Component\Security\Core\Security;

class TaskService implements ITaskService
{
	private string $guid;

	/**
	 * @param TaskRepository $taskRepository
	 * @param UserService $userService
	 * @param ActivityService $activityService
	 * @param Security $security
	 */
	public function __construct(private readonly TaskRepository  $taskRepository,
								private readonly UserService     $userService,
								private readonly ActivityService $activityService,
								private readonly Security        $security)
	{
		$this->guid = $this->security->getUser()->getUserIdentifier();
	}

	/**
	 * @return array
	 */
	public function findAllForAdmin(): array
	{
		try {
			return $this->taskRepository->findAll();
		} catch (Exception $e) {
			throw new TaskNotFoundException("Failed to find tasks");
		}
	}

	/**
	 * @return array
	 */
	public function findAllForUser(): array
	{
		try {
			$activities = $this->userService->findByGuid($this->guid)->getActivities();
			$tasks = [];

			foreach ($activities as $activity) {
				foreach ($activity->getTasks() as $task) {
					$tasks[] = $task;
				}
			}

			return $tasks;
		} catch (Exception $e) {
			throw new TaskNotFoundException("Failed to find tasks for user");
		}
	}

	/**
	 * @param string $guid
	 * @return Collection|null
	 */
	public function findAllByActivity(string $guid): Collection|null
	{
		try {
			return $this->activityService->findByGuid($guid)->getTasks();
		} catch (Exception $e) {
			throw new TaskNotFoundException("Failed to find tasks by activity");
		}
	}

	/**
	 * @param $data
	 * @return Task
	 */
	public function create($data): Task
	{
		try {
			$task = new Task();
			$task->setCompleted($data['completed']);
			$task->setDescription($data['description']);
			$task->setActivity($this->activityService->findById($data['activity']));

			$this->taskRepository->save($task, true);

			return $task;
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to create new task");
		}
	}

	/**
	 * @param $data
	 * @param Task $task
	 * @return Task
	 */
	public function update($data, Task $task): Task
	{
		try {
			$task->setDescription($data['description']);
			$task->setCompleted($data['completed']);

			$this->taskRepository->save($task, true);

			return $task;
		} catch (Exception $e) {
			throw new ResourceNotUpdatedException("Failed to update task");
		}
	}

	/**
	 * @param Task $task
	 * @return void
	 */
	public function remove(Task $task): void
	{
		try {
			$this->taskRepository->remove($task, true);
		} catch (Exception $e) {
			throw new ResourceNotDeletedException("Failed to delete task");
		}
	}
}