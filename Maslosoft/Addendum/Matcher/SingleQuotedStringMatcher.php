<?php

namespace Maslosoft\Addendum\Matcher;

class SingleQuotedStringMatcher extends RegexMatcher
{

	public function __construct()
	{
		parent::__construct("'([^']*)'");
	}

	protected function process($matches)
	{
		return $matches[1];
	}

}
