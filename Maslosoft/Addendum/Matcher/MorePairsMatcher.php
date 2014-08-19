<?php

namespace Maslosoft\Addendum\Matcher;

class MorePairsMatcher extends SerialMatcher
{

	protected function build()
	{
		$this->add(new PairMatcher);
		$this->add(new RegexMatcher('\s*,\s*'));
		$this->add(new HashMatcher);
	}

	protected function match($string, &$value)
	{
		$result = parent::match($string, $value);
		return $result;
	}

	public function process($parts)
	{
		return array_merge($parts[0], $parts[2]);
	}

}
