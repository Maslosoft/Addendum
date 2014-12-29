<?php

namespace Maslosoft\Addendum\Matcher;

class ArrayMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new ConstantMatcher('{}', []));
		$values_matcher = new SimpleSerialMatcher(1);
		$values_matcher->add(new RegexMatcher('\s*{\s*'));
		$values_matcher->add(new ArrayValuesMatcher);
		$values_matcher->add(new RegexMatcher('\s*}\s*'));
		$this->add($values_matcher);
	}

}
