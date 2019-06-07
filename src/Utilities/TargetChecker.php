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

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Exceptions\TargetException;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Check target constraints
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TargetChecker
{

	/**
	 * Check target constraints
	 * @param AnnotationInterface $annotation Annotation
	 * @param ReflectionClass|ReflectionMethod|ReflectionProperty|bool $target
	 * @throws TargetException
	 */
	public static function check($annotation, $target)
	{
		$reflection = new ReflectionAnnotatedClass($annotation);
		if (!$reflection->hasAnnotation('Target'))
		{
			return;
		}
		$value = $reflection->getAnnotation('Target')->value;
		$values = is_array($value) ? $value : [$value];
		
		foreach ($values as $value)
		{
			if ($value == TargetAnnotation::TargetClass && $target instanceof ReflectionClass)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetMethod && $target instanceof ReflectionMethod)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetProperty && $target instanceof ReflectionProperty)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetNested && $target === false)
			{
				return;
			}
		}
		if ($target !== false && $value && !in_array($value, [
					TargetAnnotation::TargetClass,
					TargetAnnotation::TargetMethod,
					TargetAnnotation::TargetProperty,
					TargetAnnotation::TargetNested
				]))
		{
			if ($target instanceof ReflectionClass)
			{
				$interfaceTarget = $target;
			}
			else
			{
				/* @var $target ReflectionProperty */
				$interfaceTarget = new ReflectionClass($target->class);
			}
			if(!ClassChecker::exists($value))
			{
				throw new TargetException(sprintf('Annotation "%s" used in "%s" is only allowed on instances of "%s", but this class does not exists (see @Target)', basename($reflection->name), $interfaceTarget->name, $value));
			}
			if (!$interfaceTarget->implementsInterface($value))
			{
				throw new TargetException(sprintf('Annotation "%s" used in "%s" is only allowed on instances of "%s" (see @Target)',  basename($reflection->name), $interfaceTarget->name, $value));
			}
		}
		if ($target === false && $value == TargetAnnotation::TargetNested)
		{
			throw new TargetException("Annotation '" . get_class($annotation) . "' nesting not allowed");
		}
		elseif (in_array($value, TargetAnnotation::getTargets()))
		{
			throw new TargetException(sprintf("Annotation '%s' not allowed on %s, it's target is %s  (see @Target)", get_class($annotation), ReflectionName::createName($target), $value));
		}
	}

}
