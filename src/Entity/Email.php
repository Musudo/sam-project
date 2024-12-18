<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EmailRepository::class)]
class Email extends Entity
{
	#[ORM\Column(length: 100)]
	#[Assert\Email(
		message: 'The email {{ value }} is not a valid email.',
	)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your email cannot be longer than {{ limit }} characters'
	)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $email = null;

	#[ORM\ManyToOne(inversedBy: 'email')]
	#[ORM\JoinColumn(nullable: false)]
	#[Assert\NotBlank]
	#[Groups('byEmail')]
	private ?Review $review = null;

	public function __construct()
	{
		parent::__construct();
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
