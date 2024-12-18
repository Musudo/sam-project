<?php

namespace App\Entity;

use App\Repository\InstitutionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InstitutionRepository::class)]
class Institution extends Entity
{
	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 255,
		maxMessage: 'Your institution name cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $name = null;

	#[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'institutions', cascade: ['persist'])]
	#[Groups('byInstitution')]
	private ?Collection $contacts;

	#[ORM\OneToMany(mappedBy: 'institution', targetEntity: Activity::class, fetch: 'LAZY')]
	#[Groups('byInstitution')]
	private Collection $activities;

	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your street cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $street = null;

	#[ORM\Column(length: 10)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 10,
		maxMessage: 'Your house number cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $houseNumber = null;

	#[ORM\Column(length: 100)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your city name cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $city = null;

	#[ORM\Column(length: 10)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 10,
		maxMessage: 'Your zip code cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $zipCode = null;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Assert\Length(
		max: 50,
		maxMessage: 'Your country name cannot be longer than {{ limit }} characters'
	)]
	#[Groups('get')]
	private ?string $country = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $longitude = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $latitude = null;

	#[ORM\Column(length: 10, nullable: true)]
	#[Groups('get')]
	private ?string $postbox = null;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $exactSales = null;

	#[ORM\Column(length: 255, nullable: true)]
	private ?string $exactGuid = null;

	#[ORM\Column(length: 50)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $clientId = null;

	#[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'institutions')]
	#[Groups('byInstitution')]
	private Collection $users;

	public function __construct()
	{
		parent::__construct();
		$this->contacts = new ArrayCollection();
		$this->activities = new ArrayCollection();
		$this->users = new ArrayCollection();
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

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
			$contact->addInstitution($this);
		}

		return $this;
	}

	public function removeContact(Contact $contact): self
	{
		if ($this->contacts->removeElement($contact)) {
			$contact->removeInstitution($this);
		}

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
			$activity->setInstitution($this);
		}

		return $this;
	}

	public function removeActivity(Activity $activity): self
	{
		if ($this->activities->removeElement($activity)) {
			// set the owning side to null (unless already changed)
			if ($activity->getInstitution() === $this) {
				$activity->setInstitution(null);
			}
		}

		return $this;
	}

	public function getStreet(): ?string
	{
		return $this->street;
	}

	public function setStreet(string $street): self
	{
		$this->street = $street;

		return $this;
	}

	public function getHouseNumber(): ?string
	{
		return $this->houseNumber;
	}

	public function setHouseNumber(string $houseNumber): self
	{
		$this->houseNumber = $houseNumber;

		return $this;
	}

	public function getCity(): ?string
	{
		return $this->city;
	}

	public function setCity(string $city): self
	{
		$this->city = $city;

		return $this;
	}

	public function getZipCode(): ?string
	{
		return $this->zipCode;
	}

	public function setZipCode(string $zipCode): self
	{
		$this->zipCode = $zipCode;

		return $this;
	}

	public function getCountry(): ?string
	{
		return $this->country;
	}

	public function setCountry(string $country): self
	{
		$this->country = $country;

		return $this;
	}

	public function getLongitude(): ?string
	{
		return $this->longitude;
	}

	public function setLongitude(?string $longitude): self
	{
		$this->longitude = $longitude;

		return $this;
	}

	public function getLatitude(): ?string
	{
		return $this->latitude;
	}

	public function setLatitude(?string $latitude): self
	{
		$this->latitude = $latitude;

		return $this;
	}

	public function getPostbox(): ?string
	{
		return $this->postbox;
	}

	public function setPostbox(?string $postbox): self
	{
		$this->postbox = $postbox;

		return $this;
	}

	public function getExactSales(): ?string
	{
		return $this->exactSales;
	}

	public function setExactSales(?string $exactSales): self
	{
		$this->exactSales = $exactSales;

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

	public function getClientId(): ?string
	{
		return $this->clientId;
	}

	public function setClientId(string $clientId): self
	{
		$this->clientId = $clientId;

		return $this;
	}

	/**
	 * @return Collection<int, User>
	 */
	public function getUsers(): Collection
	{
		return $this->users;
	}

	public function addUser(User $user): self
	{
		if (!$this->users->contains($user)) {
			$this->users->add($user);
			$user->addInstitution($this);
		}

		return $this;
	}

	public function removeUser(User $user): self
	{
		if ($this->users->removeElement($user)) {
			$user->removeInstitution($this);
		}

		return $this;
	}

	public function getAddress(): ?string
	{
		if	($this->houseNumber === 'empty' || $this->houseNumber === '--' || $this->houseNumber === '') {
			return "{$this->city} {$this->zipCode}, {$this->street}";
		} else {
			return "{$this->city} {$this->zipCode}, {$this->street} {$this->houseNumber}";
		}

	}
}
