<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
#[ORM\HasLifecycleCallbacks]
abstract class Entity
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups('get')]
	protected ?int $id = null;

	#[ORM\Column(type: Types::GUID, unique: true)]
	#[Groups('get')]
	protected ?string $guid = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'])]
	#[Groups('get')]
	protected ?\DateTimeInterface $created = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, options: ['default' => 'CURRENT_TIMESTAMP'], columnDefinition: 'DATETIME on update CURRENT_TIMESTAMP')]
	protected ?\DateTimeInterface $modified = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	protected ?\DateTimeInterface $deletedAt = null;

	public function __construct()
	{
		$this->setCreated(new \DateTime());
		if ($this->getModified() == null) {
			$this->setModified(new \DateTime());
		}
		$this->setGuid(Uuid::v4());
	}

	#[ORM\PrePersist]
	#[ORM\PreUpdate]
	protected function updateModifiedDatetime(): void
	{
		// update the modified time
		$this->setModified(new \DateTime());
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getGuid(): ?string
	{
		return $this->guid;
	}

	public function setGuid(string $guid): self
	{
		$this->guid = $guid;

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

	public function getDeletedAt(): ?\DateTimeInterface
	{
		return $this->deletedAt;
	}

	public function setDeletedAt(\DateTimeInterface $deletedAt): self
	{
		$this->deletedAt = $deletedAt;

		return $this;
	}
}