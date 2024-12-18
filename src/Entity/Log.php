<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LogRepository::class)]
class Log
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups('get')]
	private ?int $id = null;

	#[ORM\ManyToOne(inversedBy: 'logs')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byLog')]
	private ?User $user = null;

	#[ORM\Column(length: 25)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $status = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
	#[Groups('get')]
	private ?\DateTimeInterface $created = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'], columnDefinition: 'DATETIME on update CURRENT_TIMESTAMP')]
	#[Groups('get')]
	private ?\DateTimeInterface $modified = null;

	public function __construct()
	{
		$this->setCreated(new \DateTime());
		if ($this->getModified() == null) {
			$this->setModified(new \DateTime());
		}
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getStatus(): ?string
	{
		return $this->status;
	}

	public function setStatus(string $status): self
	{
		$this->status = $status;

		return $this;
	}

	public function getCreated(): ?\DateTimeInterface
	{
		return $this->created;
	}

	public function setCreated(\DateTimeInterface $created): self
	{
		$this->created = $created;

		return $this;
	}

	public function getModified(): ?\DateTimeInterface
	{
		return $this->modified;
	}

	public function setModified(\DateTimeInterface $modified): self
	{
		$this->modified = $modified;

		return $this;
	}

}
