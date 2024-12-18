<?php

namespace App\Service\Interface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface IFileService
{
	public function uploadAudio(UploadedFile $audio);
	public function uploadAttachment(UploadedFile $attachment);
	public function removeFile(string $filepath);

}