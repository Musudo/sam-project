<?php

namespace App\Logging;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;

class ColorizedFormatter extends LineFormatter
{
	protected function colorizeLevel($level, $message): string
	{
		$colorMap = [
			Logger::DEBUG => "\033[37m",	// White
			Logger::INFO => "\033[32m",		// Green
			Logger::NOTICE => "\033[36m",	// Cyan
			Logger::WARNING => "\033[33m", 	// Yellow
			Logger::ERROR => "\033[31m", 	// Red
			Logger::CRITICAL => "\033[31m", // Red
			Logger::ALERT => "\033[31m", 	// Red
			Logger::EMERGENCY => "\033[31m" // Red
		];

		$resetColor = "\033[0m";

		return $colorMap[$level] . $message . $resetColor;
	}

	public function format(array $record): string
	{
		$level = $record["level"];
		$formatted = parent::format($record);
		$coloredLine = $this->colorizeLevel($level, $formatted);

		return "\n" . $coloredLine;
	}
}