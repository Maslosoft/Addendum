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

class NumberMatcher extends RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\IMatcher
{

	public function __construct()
	{
		parent::__construct("-?[0-9]*\.?[0-9]*");
	}

	protected function process($matches)
	{
		$isFloat = strpos($matches[0], '.') !== false;
		return $isFloat ? (float) $matches[0] : (int) $matches[0];
	}

}
