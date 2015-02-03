<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Exceptions\ConflictException;
use Maslosoft\Addendum\Interfaces\IAnnotation;
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
	 * @param IAnnotation $annotation Annotation
	 * @return void
	 */
	public static function register(IAnnotation $annotation)
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
