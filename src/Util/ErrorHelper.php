<?php

namespace App\Util;

class ErrorHelper
{
	// TODO: error messages are not ver informative
	/**
	 * @param $errors
	 * @return array
	 * @uses | to get structured errors object
	 */
	public static function getErrorMessagesArray($errors): array
	{
		$errorMessages = array();
		foreach ($errors as $error) {
			$messageParameters = $error->getMessageParameters();
			$parameter = empty($messageParameters[0]) ? $error->getOrigin()->getName() : str_replace('"', '', reset($messageParameters));
			$tmpError = [
				'parameter' => $parameter,
				'message' => $error->getMessageTemplate()
			];
			$errorMessages[] = $tmpError;
		}
		return $errorMessages;
	}

	// TODO: probably redundant
	/**
	 * @param $errors
	 * @return string
	 */
	public static function errorArrayToString($errors): string
	{
		$errorStr = '';
		foreach ($errors as $error) {
			$errorStr .= $error['parameter'] . ': ' . $error['message'];
			if (next($errors)) $errorStr .= ' ';
		}
		return $errorStr;
	}
	// TODO: maybe this method is redundant
}