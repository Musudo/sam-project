<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false, hardDelete: false)]
class Contact extends Entity
{
	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Length(
		min: 2,
		max: 100,
		minMessage: 'Your first name must be at least 2 characters long',
		maxMessage: 'Your first name cannot be longer than 100 characters'
	)]
	#[Groups('get')]
	private ?string $firstName = null;

	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Length(
		min: 2,
		max: 100,
		minMessage: 'Your last name must be at least 2 characters long',
		maxMessage: 'Your last name cannot be longer than 100 characters'
	)]
	#[Groups('get')]
	private ?string $lastName = null;

	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Email(
		message: 'The email {{ value }} is not a valid email.',
	)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your email cannot be longer than 100 characters'
	)]
	#[Groups('get')]
	private ?string $email1 = null;

	#[ORM\Column(length: 100, nullable: true)]
	#[Assert\Email(
		message: 'The email {{ value }} is not a valid email.',
	)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your email cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $email2 = null;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 50,
		maxMessage: 'Your phone number cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $phoneNumber1 = null;

	#[ORM\Column(length: 50, nullable: true)]
	#[Assert\Length(
		max: 50,
		maxMessage: 'Your phone number cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $phoneNumber2 = null;

	#[ORM\ManyToMany(targetEntity: Institution::class, mappedBy: 'contacts', cascade: ['persist'])]
	#[Assert\NotBlank]
	#[Groups('byContact')]
	private Collection $institutions;

	#[ORM\ManyToMany(targetEntity: Activity::class, mappedBy: 'contacts', fetch: 'LAZY')]
	#[Groups('byContact')]
	private Collection $activities;

	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $jobTitle = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $exactGuid = null;

	public function __construct()
	{
		parent::__construct();
		$this->institutions = new ArrayCollection();
		$this->activities = new ArrayCollection();
	}

	public function getFirstName(): ?string
	{
		return $this->firstName;
	}

	public function setFirstName(string $firstName): self
	{
		$this->firstName = $firstName;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->lastName;
	}

	public function setLastName(string $lastName): self
	{
		$this->lastName = $lastName;

		return $this;
	}

	public function getEmail1(): ?string
	{
		return $this->email1;
	}

	public function setEmail1(string $email1): self
	{
		$this->email1 = $email1;

		return $this;
	}

	public function getEmail2(): ?string
	{
		return $this->email2;
	}

	public function setEmail2(?string $email2): self
	{
		$this->email2 = $email2;

		return $this;
	}

	public function getPhoneNumber1(): ?string
	{
		return $this->phoneNumber1;
	}

	public function setPhoneNumber1(string $phoneNumber1): self
	{
		$this->phoneNumber1 = $phoneNumber1;

		return $this;
	}

	public function getPhoneNumber2(): ?string
	{
		return $this->phoneNumber2;
	}

	public function setPhoneNumber2(?string $phoneNumber2): self
	{
		$this->phoneNumber2 = $phoneNumber2;

		return $this;
	}

	/**
	 * @return Collection<int, Institution>
	 */
	public function getInstitutions(): Collection
	{
		return $this->institutions;
	}

	public function addInstitution(Institution $institution): self
	{
		if (!$this->institutions->contains($institution)) {
			$this->institutions->add($institution);
		}

		return $this;
	}

	public function removeInstitution(Institution $institution): self
	{
		$this->institutions->removeElement($institution);

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

	public function getJobTitle(): ?string
	{
		return $this->jobTitle;
	}

	public function setJobTitle(string $jobTitle): self
	{
		$this->jobTitle = $jobTitle;

		return $this;
	}

	public function getExactGuid(): ?string
	{
		return $this->exactGuid;
	}

	public function setExactGuid(?string $exactGuid): self
	{
		$this->exactGuid = $exactGuid;

		return $this;
	}

	public function getFullName(): ?string
	{
		return $this->getFirstName() . ' ' . $this->getLastName();
	}
}
