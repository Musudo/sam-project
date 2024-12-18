<?php

namespace App\Service\Interface;

use App\Entity\ExternalParticipant;
use Symfony\Component\HttpFoundation\Request;

interface IExternalParticipantService
{
	public function findAllForAdmin();

	public function findByGuid(string $guid);

	public function save(ExternalParticipant $externalParticipant);

	public function remove(ExternalParticipant $externalParticipant);
}