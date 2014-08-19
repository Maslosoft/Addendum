<?php

namespace Maslosoft\Addendum\Matcher;

class TopValueMatcher extends ValueMatcher
{

	protected function process($value)
	{
		return array('value' => $value);
	}

}
