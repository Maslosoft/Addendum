<?php

namespace Maslosoft\Addendum\Matcher;

class ValuesMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new TopValueMatcher);
		$this->add(new HashMatcher);
	}

}
