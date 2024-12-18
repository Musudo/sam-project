<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email')]
class User extends Entity implements UserInterface
{
	#[ORM\Column(length: 100, unique: true)]
	#[Assert\NotBlank]
	#[Assert\Email(
		message: 'The email {{ value }} is not a valid email.',
	)]
	#[Assert\Length(
		max: 100,
		maxMessage: 'Your email cannot be longer than {{ value }} characters'
	)]
	#[Groups('get')]
	private ?string $email = null;

	#[ORM\Column(type: 'json')]
	#[Assert\NotBlank]
	#[Groups('get')]
	private array $roles = [];

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $firstName = null;

	#[ORM\Column(length: 255)]
	#[Assert\NotBlank]
	#[Groups('get')]
	private ?string $lastName = null;

	#[ORM\OneToMany(mappedBy: 'user', targetEntity: Activity::class, fetch: 'LAZY')]
	#[Groups('byUser')]
	private Collection $activities;

	#[ORM\OneToMany(mappedBy: 'user', targetEntity: Log::class)]
	private Collection $logs;

	#[ORM\Column(length: 50, nullable: true)]
	private ?string $exactSales = null;

	#[ORM\ManyToMany(targetEntity: Institution::class, inversedBy: 'users')]
	#[Groups('byUser')]
	private Collection $institutions;

	#[ORM\OneToMany(mappedBy: 'user', targetEntity: Review::class)]
	#[Groups('byUser')]
	private Collection $reviews;

	public function __construct()
	{
		parent::__construct();
		$this->activities = new ArrayCollection();
		$this->institutions = new ArrayCollection();
		$this->logs = new ArrayCollection();
		$this->reviews = new ArrayCollection();
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
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string)$this->guid;
	}

	/**
	 * @deprecated since Symfony 5.3, use getUserIdentifier instead
	 */
	public function getUsername(): string
	{
		return (string)$this->email;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
	 *
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): ?string
	{
		return null;
	}

	/**
	 * This method can be removed in Symfony 6.0 - is not needed for apps that do not check user passwords.
	 *
	 * @see UserInterface
	 */
	public function getSalt(): ?string
	{
		return null;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive public on the user, clear it here
		// $this->plainPassword = null;
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

	public function getFullName(): ?string
	{
		return $this->firstName . ' ' . $this->lastName;
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
			$activity->setUser($this);
		}

		return $this;
	}

	public function removeActivity(Activity $activity): self
	{
		if ($this->activities->removeElement($activity)) {
			// set the owning side to null (unless already changed)
			if ($activity->getUser() === $this) {
				$activity->setUser(null);
			}
		}

		return $this;
	}

	/**
	 * @return Collection<int, Log>
	 */
	public function getLogs(): Collection
	{
		return $this->logs;
	}

	public function addLog(Log $log): self
	{
		if (!$this->logs->contains($log)) {
			$this->logs->add($log);
			$log->setUser($this);
		}

		return $this;
	}

	public function removeLog(Log $log): self
	{
		if ($this->logs->removeElement($log)) {
			// set the owning side to null (unless already changed)
			if ($log->getUser() === $this) {
				$log->setUser(null);
			}
		}

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
	 * @return Collection<int, Review>
	 */
	public function getReviews(): Collection
	{
		return $this->reviews;
	}

	public function addReview(Review $review): self
	{
		if (!$this->reviews->contains($review)) {
			$this->reviews->add($review);
			$review->setUser($this);
		}

		return $this;
	}

	public function removeReview(Review $review): self
	{
		if ($this->reviews->removeElement($review)) {
			// set the owning side to null (unless already changed)
			if ($review->getUser() === $this) {
				$review->setUser(null);
			}
		}

		return $this;
	}

}
