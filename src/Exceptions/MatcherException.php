<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.12.17
 * Time: 11:11
 */

namespace Maslosoft\Addendum\Exceptions;


use Exception;
use Throwable;

class MatcherException extends Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		$message .= $this->errorToText($code);
		parent::__construct($message, $code, $previous);
	}

	private function errorToText($errcode)
	{
		static $messages;

		if (!isset($errtxt))
		{
			$messages = array();
			$constants = get_defined_constants(true);
			foreach ($constants['pcre'] as $name => $code)
			{
				if (preg_match('/_ERROR$/', $name))
				{
					$messages[$code] = $name;
				}
			}
		}

		return array_key_exists($errcode, $messages)? $messages[$errcode] : NULL;
	}
}