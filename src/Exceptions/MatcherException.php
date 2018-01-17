<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Exceptions;


use Exception;
use Throwable;

class MatcherException extends Exception
{
	public function __construct($message = "", $code = 0, Throwable $previous = null)
	{
		$message .= $this->errorToText($code, $message);
		parent::__construct($message, $code, $previous);
	}

	private function errorToText($errcode, $errtxt)
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