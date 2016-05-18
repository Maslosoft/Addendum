<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Utilities;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * ReflectionName
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ReflectionName
{

	/**
	 * Create class name
	 * @param ReflectionClass|ReflectionMethod|ReflectionProperty|bool $target
	 * @return string
	 */
	public static function createName($target)
	{
		if (!$target)
		{
			return 'unknown';
		}
		if ($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->name . '@' . $target->name . '()';
		}
		elseif ($target instanceof ReflectionProperty)
		{
			return $target->getDeclaringClass()->name . '@$' . $target->name;
		}
		else
		{
			return $target->name;
		}
	}

}
