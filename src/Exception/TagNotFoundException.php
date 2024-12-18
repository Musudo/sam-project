<?php

namespace App\Exception;

use RuntimeException;

class TagNotFoundException extends RuntimeException
{
	public function __construct(string $message)
	{
		parent::__construct($message);
	}
}