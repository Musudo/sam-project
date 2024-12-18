<?php

namespace App\Service;

use App\Entity\Email;
use App\Exception\ResourceNotCreatedException;
use App\Repository\EmailRepository;
use App\Service\Interface\IEmailEntityService;
use Exception;

class EmailEntityService implements IEmailEntityService
{
	/**
	 * @param EmailRepository $emailRepository
	 */
	public function __construct(private readonly EmailRepository $emailRepository)
	{
	}

	/**
	 * @param Email $email
	 * @return void
	 */
	public function save(Email $email): void
	{
		try {
			$this->emailRepository->save($email, true);
		} catch (Exception $e) {
			throw new ResourceNotCreatedException("Failed to save email");
		}
	}
}