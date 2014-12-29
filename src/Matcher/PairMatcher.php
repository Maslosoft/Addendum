<?php

namespace Maslosoft\Addendum\Matcher;

class PairMatcher extends SerialMatcher
{

	protected function build()
	{
		$this->add(new KeyMatcher);
		$this->add(new RegexMatcher('\s*=\s*'));
		$this->add(new ValueMatcher);
	}

	protected function process($parts)
	{
		return [$parts[0] => $parts[2]];
	}

}
