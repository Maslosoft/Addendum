<?php

namespace Maslosoft\Addendum\Matcher;

class TopValueMatcher extends ValueMatcher
{

	protected function process($value)
	{
		return ['value' => $value];
	}

}
