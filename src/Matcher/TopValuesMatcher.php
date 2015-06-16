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

/**
 * TopValuesMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TopValuesMatcher extends ParallelMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
{

	protected function build()
	{
		$this->add((new NonArrayMatcher)->setPlugins($this->getPlugins()));
		$this->add((new MoreValuesMatcher)->setPlugins($this->getPlugins()));
	}


	protected function process($value)
	{
		return ['value' => $value];
	}
}
