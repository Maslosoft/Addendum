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

use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Exceptions\ConflictException;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * ConflictChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ConflictChecker
{

	private static $_conflicts = [];

	/**
	 * Register annotation for later check
	 * @param AnnotationInterface $annotation Annotation
	 * @return void
	 */
	public static function register(AnnotationInterface $annotation)
	{
		$name = AnnotationName::createName($annotation);

		$reflection = new ReflectionAnnotatedClass($annotation);
		if ($reflection->hasAnnotation('Conflicts'))
		{
			$value = $reflection->getAnnotation('Conflicts')->value;
			$values = is_array($value) ? $value : [$value];
			foreach($values as $secondName)
			{
				self::$_conflicts[$name] = $secondName;
				self::$_conflicts[$secondName] = $name;
			}
		}
	}

	/**
	 * Check target constraints
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|bool $target Target entity
	 * @param AnnotationsCollection $annotations
	 * @return void
	 * @throws ConflictException
	 */
	public static function check($target, AnnotationsCollection $annotations)
	{
		if (!self::$_conflicts)
		{
			return;
		}
		foreach ($annotations->getAllAnnotations() as $annotation)
		{
			$name = AnnotationName::createName($annotation);
			if (!isset(self::$_conflicts[$name]))
			{
				continue;
			}
			$second = self::$_conflicts[$name];
			if ($annotations->hasAnnotation($second))
			{
				throw new ConflictException(sprintf('Annotation `%s` cannot be used together with `%s` in `%s`', $name, $second, ReflectionName::createName($target)));
			}
		}
	}

}
