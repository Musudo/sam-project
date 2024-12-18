<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
class Activity extends Entity
{
	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $type = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	#[Assert\NotBlank]
	#[Assert\Type(\DateTime::class)]
	#[Groups('get')]
	private ?\DateTimeInterface $start = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE)]
	#[Assert\NotBlank]
	#[Assert\Type(\DateTime::class)]
	#[Groups('get')]
	private ?\DateTimeInterface $end = null;

	#[ORM\ManyToOne(inversedBy: 'activities')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byActivity')]
	private ?User $user = null;

	#[ORM\OneToOne(mappedBy: 'activity', cascade: ['persist', 'remove'])]
	#[Groups('byActivity')]
	private ?VoiceMemo $voiceMemo = null;

	#[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'activities')]
	#[Groups('byActivity')]
	private Collection $contacts;

	#[ORM\OneToMany(mappedBy: 'activity', targetEntity: Task::class, orphanRemoval: true)]
	#[Groups('byActivity')]
	private Collection $tasks;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 50,
		maxMessage: 'Your subject cannot be longer than 50 characters'
	)]
	#[Groups('get')]
	private ?string $subject = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	#[Groups('get')]
	private ?string $externalNote = null;

	#[ORM\Column(type: Types::TEXT, nullable: true)]
	#[Groups('get')]
	private ?string $internalNote = null;

	#[ORM\ManyToOne(inversedBy: 'activities')]
	#[ORM\JoinColumn(nullable: false)]
	#[Groups('byActivity')]
	private ?Institution $institution = null;

	#[ORM\ManyToMany(targetEntity: ExternalParticipant::class, inversedBy: 'activities', cascade: ['persist', 'remove'])]
	#[Groups('byActivity')]
	private Collection $externalParticipants;

	#[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'activities')]
	#[Assert\NotBlank]
	#[Groups('byActivity')]
	private Collection $tags;

	#[ORM\OneToOne(mappedBy: 'activity', cascade: ['persist', 'remove'])]
	#[Groups('byActivity')]
	private ?Review $review = null;

	#[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
	#[Assert\Type(\DateTime::class)]
	#[Groups('get')]
	private ?\DateTimeInterface $emailSentAt = null;

	public function __construct()
	{
		parent::__construct();
		$this->contacts = new ArrayCollection();
		$this->tasks = new ArrayCollection();
		$this->externalParticipants = new ArrayCollection();
		$this->tags = new ArrayCollection();
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(string $type): self
	{
		$this->type = $type;

		return $this;
	}

	public function getStart(): ?\DateTimeInterface
	{
		return $this->start;
	}

	public function setStart(\DateTimeInterface $start): self
	{
		$this->start = $start;

		return $this;
	}

	public function getEnd(): ?\DateTimeInterface
	{
		return $this->end;
	}

	public function setEnd(\DateTimeInterface $end): self
	{
		$this->end = $end;

		return $this;
	}

	public function getUser(): ?user
	{
		return $this->user;
	}

	public function setUser(?user $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getVoiceMemo(): ?VoiceMemo
	{
		return $this->voiceMemo;
	}

	public function setVoiceMemo(?VoiceMemo $voiceMemo): self
	{
		// unset the owning side of the relation if necessary
		if ($voiceMemo === null && $this->voiceMemo !== null) {
			$this->voiceMemo->setActivity(null);
		}

		// set the owning side of the relation if necessary
		if ($voiceMemo !== null && $voiceMemo->getActivity() !== $this) {
			$voiceMemo->setActivity($this);
		}

		$this->voiceMemo = $voiceMemo;

		return $this;
	}

	/**
	 * @return Collection<int, Contact>
	 */
	public function getContacts(): Collection
	{
		return $this->contacts;
	}

	public function addContact(Contact $contact): self
	{
		if (!$this->contacts->contains($contact)) {
			$this->contacts->add($contact);
			$contact->addActivity($this);
		}

		return $this;
	}

	public function removeContact(Contact $contact): self
	{
		if ($this->contacts->removeElement($contact)) {
			$contact->removeActivity($this);
		}

		return $this;
	}

	/**
	 * @return Collection<int, Task>
	 */
	public function getTasks(): Collection
	{
		return $this->tasks;
	}

	public function addTask(Task $task): self
	{
		if (!$this->tasks->contains($task)) {
			$this->tasks->add($task);
			$task->setActivity($this);
		}

		return $this;
	}

	public function removeTask(Task $task): self
	{
		if ($this->tasks->removeElement($task)) {
			// set the owning side to null (unless already changed)
			if ($task->getActivity() === $this) {
				$task->setActivity(null);
			}
		}

		return $this;
	}

	public function getSubject(): ?string
	{
		return $this->subject;
	}

	public function setSubject(string $subject): self
	{
		$this->subject = $subject;

		return $this;
	}

	public function getExternalNote(): ?string
	{
		return $this->externalNote;
	}

	public function setExternalNote(?string $externalNote): self
	{
		$this->externalNote = $externalNote;

		return $this;
	}

	public function getInternalNote(): ?string
	{
		return $this->internalNote;
	}

	public function setInternalNote(?string $internalNote): self
	{
		$this->internalNote = $internalNote;

		return $this;
	}

	public function getInstitution(): ?Institution
	{
		return $this->institution;
	}

	public function setInstitution(?Institution $institution): self
	{
		$this->institution = $institution;

		return $this;
	}

	/**
	 * @return Collection<int, ExternalParticipant>
	 */
	public function getExternalParticipants(): Collection
	{
		return $this->externalParticipants;
	}

	public function addExternalParticipant(ExternalParticipant $externalParticipant): self
	{
		if (!$this->externalParticipants->contains($externalParticipant)) {
			$this->externalParticipants->add($externalParticipant);
			$externalParticipant->addActivity($this);
		}

		return $this;
	}

	public function removeExternalParticipant(ExternalParticipant $externalParticipant): self
	{
		if ($this->externalParticipants->removeElement($externalParticipant)) {
			$externalParticipant->removeActivity($this);
		}

		return $this;
	}

	/**
	 * @return Collection<int, Tag>
	 */
	public function getTags(): Collection
	{
		return $this->tags;
	}

	public function addTag(Tag $tag): self
	{
		if (!$this->tags->contains($tag)) {
			$this->tags->add($tag);
			$tag->addActivity($this);
		}

		return $this;
	}

	public function removeTag(Tag $tag): self
	{
		if ($this->tags->removeElement($tag)) {
			$tag->removeActivity($this);
		}

		return $this;
	}

	public function getReview(): ?Review
	{
		return $this->review;
	}

	public function setReview(Review $review): self
	{
		// set the owning side of the relation if necessary
		if ($review->getActivity() !== $this) {
			$review->setActivity($this);
		}

		$this->review = $review;

		return $this;
	}

	public function getEmailSentAt(): ?\DateTimeInterface
	{
		return $this->emailSentAt;
	}

	public function setEmailSentAt(?\DateTimeInterface $emailSentAt): static
	{
		$this->emailSentAt = $emailSentAt;

		return $this;
	}

}
