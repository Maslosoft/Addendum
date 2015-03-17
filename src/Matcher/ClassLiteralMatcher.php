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
 * ClassLiteralMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassLiteralMatcher extends RegexMatcher implements \Maslosoft\Addendum\Interfaces\Matcher\IMatcher
{
	public function __construct()
	{
		parent::__construct('([A-Z\\\][a-zA-Z0-9_\\\]+)');
	}

	protected function process($matches)
	{
		return $matches[1];
	}
}
