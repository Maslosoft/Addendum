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

class ArrayMatcher extends ParallelMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{

	protected function build()
	{
		$this->add((new ConstantMatcher('{}', []))->setPlugins($this->getPlugins()));
		$valuesMatcher = new SimpleSerialMatcher(1);
		$valuesMatcher->setPlugins($this->getPlugins());
		$valuesMatcher->add((new RegexMatcher('\s*{\s*'))->setPlugins($this->getPlugins()));
		$valuesMatcher->add((new ArrayValuesMatcher)->setPlugins($this->getPlugins()));
		$valuesMatcher->add((new RegexMatcher('\s*}\s*'))->setPlugins($this->getPlugins()));
		$this->add($valuesMatcher);
	}

}
