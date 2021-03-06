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

class MoreValuesMatcher extends SimpleSerialMatcher implements MatcherInterface
{

	protected function build()
	{
		$this->add((new ArrayValueMatcher)->setPlugins($this->getPlugins()));
		$this->add((new RegexMatcher('\s*,\s*'))->setPlugins($this->getPlugins()));
		$this->add((new ArrayValuesMatcher)->setPlugins($this->getPlugins()));
	}

	protected function match($string, &$value)
	{
		$result = parent::match($string, $value);
		return $result;
	}

	public function process($parts)
	{
		return array_merge($parts[0], $parts[2]);
	}

}
