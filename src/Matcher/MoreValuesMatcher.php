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

class MoreValuesMatcher extends SimpleSerialMatcher
{

	protected function build()
	{
		$this->add(new ArrayValueMatcher);
		$this->add(new RegexMatcher('\s*,\s*'));
		$this->add(new ArrayValuesMatcher);
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
