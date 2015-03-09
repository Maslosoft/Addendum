<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class ParallelMatcher extends CompositeMatcher
{

	protected function match($string, &$value)
	{
		$maxLength = false;
		$result = null;
		$subvalue = [];
		foreach ($this->matchers as $matcher)
		{
			$length = $matcher->matches($string, $subvalue);
			if ($maxLength === false || $length > $maxLength)
			{
				$maxLength = $length;
				$result = $subvalue;
			}
		}
		$value = $this->process($result);
		return $maxLength;
	}

	protected function process($value)
	{
		return $value;
	}

}
