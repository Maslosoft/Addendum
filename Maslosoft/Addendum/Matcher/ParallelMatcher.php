<?php

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
