<?php

namespace Maslosoft\Addendum\Matcher;

class HashMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new PairMatcher);
		$this->add(new MorePairsMatcher);
	}

}
