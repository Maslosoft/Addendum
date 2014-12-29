<?php

namespace Maslosoft\Addendum\Matcher;

class KeyMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new RegexMatcher('[a-zA-Z_][a-zA-Z0-9_]*'));
		$this->add(new StringMatcher);
		$this->add(new IntegerMatcher);
	}

}
