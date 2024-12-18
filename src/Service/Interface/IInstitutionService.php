<?php

namespace App\Service\Interface;

interface IInstitutionService
{
	public function findAllForAdmin();

	public function findByInfoForAdmin(string $param);

	public function findAllForUser();

	public function findByInfoForUser(string $param);

	public function findByGuid(string $guid);

	public function findById(int $id);
}