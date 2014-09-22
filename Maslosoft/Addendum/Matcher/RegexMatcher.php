<?php

namespace Maslosoft\Addendum\Matcher;

class RegexMatcher
{

	private $regex;

	public function __construct($regex)
	{
		$this->regex = $regex;
	}

	public function matches($string, &$value)
	{
		$matches = [];
		if (preg_match("/^{$this->regex}/", $string, $matches))
		{
			$value = $this->process($matches);
			return strlen($matches[0]);
		}
		$value = false;
		return false;
	}

	protected function process($matches)
	{
		return $matches[0];
	}

}
