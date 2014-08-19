<?php

namespace Maslosoft\Addendum\Matcher;

class ArrayValuesMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new ArrayValueMatcher);
		$this->add(new MoreValuesMatcher);
	}

}
