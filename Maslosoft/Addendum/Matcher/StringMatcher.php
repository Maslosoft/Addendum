<?php

namespace Maslosoft\Addendum\Matcher;

class StringMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new SingleQuotedStringMatcher);
		$this->add(new DoubleQuotedStringMatcher);
	}

}
