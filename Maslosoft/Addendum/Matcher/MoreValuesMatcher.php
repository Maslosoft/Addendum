<?php

namespace Maslosoft\Addendum\Matcher;

class MoreValuesMatcher extends SimpleSerialMatcher
{

	protected function build()
	{
		$this->add(new ArrayValueMatcher);
		$this->add(new RegexMatcher('\s*,\s*'));
		$this->add(new ArrayValuesMatcher);
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
