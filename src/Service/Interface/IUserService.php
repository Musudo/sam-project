<?php

namespace App\Service\Interface;

interface IUserService
{
	public function findAll();

	public function findByGuid(string $guid);
}