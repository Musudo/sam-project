<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
class Attachment extends Entity
{
	#[ORM\Column(length: 255)]
	#[Assert\Length(
		max: 255,
		maxMessage: 'Your path cannot be longer than {{ limit }} characters'
	)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $path = null;

	#[ORM\ManyToOne(inversedBy: 'attachments')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byAttachment')]
	private ?Review $review = null;

	public function __construct()
	{
		parent::__construct();
	}

	public function getPath(): ?string
	{
		return $this->path;
	}

	public function setPath(string $path): self
	{
		$this->path = $path;

		return $this;
	}

	public function getReview(): ?Review
	{
		return $this->review;
	}

	public function setReview(?Review $review): self
	{
		$this->review = $review;

		return $this;
	}
}
