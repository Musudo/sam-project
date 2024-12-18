<?php

namespace App\Service;

use App\Entity\User;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use App\Service\Interface\IUserService;
use Exception;

class UserService implements IUserService
{

	/**
	 * @param UserRepository $userRepository
	 */
	public function __construct(private readonly UserRepository $userRepository)
	{
	}

	/**
	 * @return User[]
	 */
	public function findAll(): array
	{
		try {
			return $this->userRepository->findAll();
		} catch (Exception $e) {
			throw new UserNotFoundException("Failed to find users");
		}
	}

	/**
	 * @param string $guid
	 * @return User|null
	 */
	public function findByGuid(string $guid): ?User
	{
		try {
			return $this->userRepository->findOneBy(['guid' => $guid]);
		} catch (Exception $e) {
			throw new UserNotFoundException("Failed to find user by guid");
		}
	}
}