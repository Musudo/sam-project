<?php

namespace App\Service\Interface;

use App\Entity\Task;

interface ITaskService
{
	public function findAllForAdmin();

	public function findAllForUser();

	public function findAllByActivity(string $guid);

	public function create($data);

	public function update($data, Task $task);

	public function remove(Task $task);
}