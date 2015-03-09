<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

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
