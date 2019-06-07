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

namespace Maslosoft\Addendum\Helpers;

use ReflectionClass;
use ReflectionObject;
use Reflector;

/**
 * Coarse check for annotations.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CoarseChecker
{

	/**
	 * Check if file contains annotations,
	 * by checking if it contains @[A-Z] regular expression.
	 *
	 * It does not ensure that file really contains annotations.
	 *
	 * @param string|Reflector|object $entity
	 * @return bool
	 */
	public static function mightHaveAnnotations($entity)
	{
		if (is_object($entity))
		{
			if ($entity instanceof Reflector)
			{
				if ($entity instanceof ReflectionClass)
				{
					$file = $entity->getFileName();
				}
				else
				{
					$file = $entity->getDeclaringClass()->getFileName();
				}
			}
			else
			{
				$file = (new ReflectionObject($entity))->getFileName();
			}
		}
		else
		{
			$file = $entity;
		}
		if (empty($file) || !is_string($file))
		{
			return false;
		}
		$content = file_get_contents($file);
		return !!preg_match('~@[A-Z]~', $content);
	}

}
