<?php

namespace Maslosoft\Addendum\Matcher;

class ParametersMatcher extends ParallelMatcher
{

	protected function build()
	{
		$this->add(new ConstantMatcher('', array()));
		$this->add(new ConstantMatcher('\(\)', array()));
		$params_matcher = new SimpleSerialMatcher(1);
		$params_matcher->add(new RegexMatcher('\(\s*'));
		$params_matcher->add(new ValuesMatcher);
		$params_matcher->add(new RegexMatcher('\s*\)'));
		$this->add($params_matcher);
	}

}
