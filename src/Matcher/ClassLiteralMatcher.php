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

use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;
use Maslosoft\Addendum\Matcher\Helpers\Processor;
use Maslosoft\Addendum\Utilities\ClassChecker;

/**
 * ClassLiteralMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassLiteralMatcher implements IMatcher
{

	use Traits\PluginsTrait;

	protected function process($matches)
	{
		return Processor::process($this, $matches[1]);
	}

	public function matches($string, &$value)
	{
		$matches = [];
		$regex = '([A-Z\\\][a-zA-Z0-9_\\\]+)';
		if (preg_match("/^{$regex}/", $string, $matches))
		{
			$value = $this->process($matches);
			if (!ClassChecker::exists($value))
			{
				return false;
			}
			return strlen($matches[0]);
		}
		$value = false;
		return false;
	}

}
