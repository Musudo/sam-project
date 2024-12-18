<?php

namespace App\Entity;

use App\Repository\ExternalParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExternalParticipantRepository::class)]
class ExternalParticipant extends Entity
{
	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Email(
		message: 'The email {{ value }} is not a valid email.',
	)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your email {{ value }} cannot be longer than 100 characters'
	)]
	#[Groups('get')]
    private ?string $email = null;

    #[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'externalParticipants', cascade: ['persist'], fetch: 'LAZY')]
	#[Groups('byExternalParticipant')]
    private Collection $activities;

    public function __construct()
    {
		parent::__construct();
        $this->activities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Activity>
     */
    public function getActivities(): Collection
    {
        return $this->activities;
    }

    public function addActivity(Activity $activity): self
    {
        if (!$this->activities->contains($activity)) {
            $this->activities->add($activity);
        }

        return $this;
    }

    public function removeActivity(Activity $activity): self
    {
        $this->activities->removeElement($activity);

        return $this;
    }
}
