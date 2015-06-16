<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

class SerialMatcher extends CompositeMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{

	protected function match($string, &$value)
	{
		$results = [];
		$result = [];
		$total_length = 0;
		foreach ($this->matchers as $matcher)
		{
			if (($length = $matcher->matches($string, $result)) === false)
			{
				return false;
			}
			$total_length += $length;
			$results[] = $result;
			$string = substr($string, $length);
		}
		$value = $this->process($results);
		return $total_length;
	}

	/**
	 * 
	 * @param mixed[] $results
	 * @return mixed
	 */
	protected function process($results)
	{
		return implode('', $results);
	}

}
