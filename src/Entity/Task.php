<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task extends Entity
{
    #[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 255,
		maxMessage: 'Your task description cannot be longer than 255 characters'
	)]
	#[Groups('get')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
	#[Groups('get')]
    private ?bool $completed = null;

    #[ORM\ManyToOne(inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byTask')]
    private ?Activity $activity = null;

	public function __construct()
	{
		parent::__construct();
	}

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isCompleted(): ?bool
    {
        return $this->completed;
    }

    public function setCompleted(?bool $completed): self
    {
        $this->completed = $completed;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }
}
