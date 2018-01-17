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

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Interfaces\AnnotatedReflectorInterface;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * IgnoredChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IgnoredChecker
{

	/**
	 * Check if entity is ignored
	 * @param ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|AnnotatedReflectorInterface $target
	 * @return bool
	 */
	public static function check(AnnotatedReflectorInterface $target)
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
