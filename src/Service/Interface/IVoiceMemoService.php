<?php

namespace App\Service\Interface;

use App\Entity\VoiceMemo;

interface IVoiceMemoService
{
	public function add($audio, int $activityId);

	public function save(VoiceMemo $voiceMemo);

	public function remove(int $activityId);
}