<?php

namespace Maslosoft\Addendum\Matcher;

class ValueInArrayMatcher extends ValueMatcher
{

	public function process($value)
	{
		return [$value];
	}

}
