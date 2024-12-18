<?php

namespace App\Service\Interface;

use App\Entity\Email;

interface IEmailEntityService
{
	public function save(Email $email);
}