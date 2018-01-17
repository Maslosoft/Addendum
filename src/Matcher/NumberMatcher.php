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

class NumberMatcher extends RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface
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
