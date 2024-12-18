<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review extends Entity
{
	#[ORM\Column(length: 100)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your title cannot be longer than {{ limit }} characters'
	)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $title = null;

	#[ORM\Column(type: Types::TEXT)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $content = null;

	#[ORM\OneToOne(inversedBy: 'review', cascade: ['persist', 'remove'])]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byReview')]
	private ?Activity $activity = null;

	#[ORM\ManyToOne(inversedBy: 'reviews')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byReview')]
	private ?User $user = null;

	#[ORM\OneToMany(mappedBy: 'review', targetEntity: Email::class, orphanRemoval: true)]
	#[Groups('byReview')]
	private Collection $emails;

	#[ORM\OneToMany(mappedBy: 'review', targetEntity: Attachment::class, orphanRemoval: true)]
	#[Groups(['byReview', 'byActivity'])]
	private Collection $attachments;

	public function __construct()
	{
		parent::__construct();

		$this->emails = new ArrayCollection();
		$this->attachments = new ArrayCollection();
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;

		return $this;
	}

	public function getActivity(): ?Activity
	{
		return $this->activity;
	}

	public function setActivity(Activity $activity): self
	{
		$this->activity = $activity;

		return $this;
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

	/**
	 * @return Collection<int, Email>
	 */
	public function getEmails(): Collection
	{
		return $this->emails;
	}

	public function addEmail(Email $email): self
	{
		if (!$this->emails->contains($email)) {
			$this->emails->add($email);
			$email->setReview($this);
		}

		return $this;
	}

	public function removeEmail(Email $email): self
	{
		if ($this->emails->removeElement($email)) {
			// set the owning side to null (unless already changed)
			if ($email->getReview() === $this) {
				$email->setReview(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Attachment>
	 */
	public function getAttachments(): Collection
	{
		return $this->attachments;
	}

	public function addAttachment(Attachment $attachment): self
	{
		if (!$this->attachments->contains($attachment)) {
			$this->attachments->add($attachment);
			$attachment->setReview($this);
		}

		return $this;
	}

	public function removeAttachment(Attachment $attachment): self
	{
		if ($this->attachments->removeElement($attachment)) {
			// set the owning side to null (unless already changed)
			if ($attachment->getReview() === $this) {
				$attachment->setReview(null);
			}
		}

		return $this;
	}

}
