<?php

namespace Maslosoft\Addendum\Matcher;

class ArrayValueMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new ValueInArrayMatcher);
		$this->add(new PairMatcher);
	}

}
