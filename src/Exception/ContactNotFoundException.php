<?php

namespace App\Exception;

use RuntimeException;

class ContactNotFoundException extends RuntimeException
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}