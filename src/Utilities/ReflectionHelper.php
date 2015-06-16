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

use Exception;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * ReflectionHelper
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ReflectionHelper
{

	/**
	 * Get reflection class.
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedProperty|ReflectionAnnotatedMethod $reflection Reflection
	 * @return ReflectionAnnotatedClass
	 * @throws Exception
	 */
	public static function getReflectionClass($reflection)
	{
		if (null === $reflection)
		{
			throw new Exception(sprintf('No reflection class for matcher `%s`', get_class($this)));
		}
		if ($reflection instanceof ReflectionAnnotatedMethod)
		{
			return $reflection->getDeclaringClass();
		}
		if ($reflection instanceof ReflectionAnnotatedProperty)
		{
			return $reflection->getDeclaringClass();
		}
		return $reflection;
	}

}
