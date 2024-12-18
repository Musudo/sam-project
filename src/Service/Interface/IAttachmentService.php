<?php

namespace App\Service\Interface;

use App\Entity\Attachment;

interface IAttachmentService
{
	public function findById(int $id);
	public function add($attachment, int $reviewId);
	public function save(Attachment $attachment);
	public function remove(int $id);
}