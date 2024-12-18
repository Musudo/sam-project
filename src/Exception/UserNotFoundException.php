<?php

namespace App\Exception;

class UserNotFoundException extends \RuntimeException
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}