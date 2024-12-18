<?php

namespace App\Service;

use App\Service\Interface\IFileService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileService implements IFileService
{
	private readonly Filesystem $filesystem;

	public function __construct(private readonly ParameterBagInterface $parameterBag)
	{
		$this->filesystem = new Filesystem();
	}

	/**
	 * save audio record in uploads directory for audios
	 *
	 * @param UploadedFile $audio
	 * @return string
	 */
	public function uploadAudio(UploadedFile $audio): string
	{
		if ($audio->isValid()) {
			$directory = $this->parameterBag->get('kernel.project_dir') . '/public/audio';
			$filename = uniqid('voice-memo-') . '.mp3';
			$filepath = Path::join($directory, $filename);

			$this->filesystem->dumpFile($filepath, $audio->getContent());

			return $filepath;
		} else {
			return throw new IOException("Uploaded file is not valid");
		}
	}

	/**
	 * save attachment in uploads directory for attachments
	 *
	 * @param UploadedFile $attachment
	 * @return string
	 */
	public function uploadAttachment(UploadedFile $attachment): string
	{
		$allowedExtensions = ['csv', 'txt', 'doc', 'docx', 'xls', 'xlsx', 'pdf'];

		if ($attachment->isValid()) {
			if (in_array($attachment->getClientOriginalExtension(), $allowedExtensions)) {
				$directory = $this->parameterBag->get('kernel.project_dir') . '/public/attachments';
//				$filename = uniqid('attachment-') . '.' . $attachment->getClientOriginalExtension();
				$filename = $attachment->getClientOriginalName();
				$filepath = Path::join($directory, $filename);

				$this->filesystem->dumpFile($filepath, $attachment->getContent());

				return $filepath;
			} else {
				return throw new IOException("Uploaded file is not allowed");
			}
		} else {
			return throw new IOException("Uploaded file is not valid");
		}
	}

	/**
	 * delete file of any type
	 *
	 * @param string $filepath
	 * @return void
	 */
	public function removeFile(string $filepath): void
	{
		$this->filesystem->remove($filepath);
	}
}