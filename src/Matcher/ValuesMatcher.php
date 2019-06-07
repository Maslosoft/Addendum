<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;

class ValuesMatcher extends ParallelMatcher implements MatcherInterface
{

	protected function build()
	{
		$this->add((new TopValueMatcher)->setPlugins($this->getPlugins()));
		$this->add((new TopValuesMatcher)->setPlugins($this->getPlugins()));
		$this->add((new HashMatcher)->setPlugins($this->getPlugins()));
	}

}
