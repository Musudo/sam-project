<?php

namespace App\Entity;

use App\Repository\VoiceMemoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoiceMemoRepository::class)]
class VoiceMemo extends Entity
{
    #[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Groups('get')]
    private ?string $path = null;

    #[ORM\OneToOne(inversedBy: 'voiceMemo')]
	#[Assert\NotBlank]
	#[Groups('byVoiceMemo')]
    private ?Activity $activity = null;

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
