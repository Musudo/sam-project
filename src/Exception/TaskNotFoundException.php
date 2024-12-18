<?php

namespace App\Exception;

use RuntimeException;

class TaskNotFoundException extends RuntimeException
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}