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

class KeyMatcher extends ParallelMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{

	protected function build()
	{
		$this->add((new RegexMatcher('[a-zA-Z_][a-zA-Z0-9_]*'))->setPlugins($this->getPlugins()));
		$this->add((new StringMatcher)->setPlugins($this->getPlugins()));
		$this->add((new IntegerMatcher)->setPlugins($this->getPlugins()));
	}

}
