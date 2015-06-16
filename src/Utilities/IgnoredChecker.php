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

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Reflector;

/**
 * IgnoredChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IgnoredChecker
{

	/**
	 * Check if entity is ignored
	 * @param ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $target
	 * @return bool
	 */
	public static function check(Reflector $target)
	{
		if (!$target->hasAnnotation('Ignored'))
		{
			return false;
		}

		$value = $target->getAnnotation('Ignored')->value;
		if (false === $value)
		{
			return false;
		}
		return true;
	}

}
