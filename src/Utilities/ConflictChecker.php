<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

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

	/**
	 * Check target constraints
	 * @param IAnnotation $annotation Annotation
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|bool $target Target entity
	 * @return type
	 * @throws ConflictException
	 */
	public static function check($annotation, $target)
	{
		if(!$target)
		{
			return;
		}
		$reflection = new ReflectionAnnotatedClass($annotation);
		if (!$reflection->hasAnnotation('Conflicts'))
		{
			return;
		}
		$value = $reflection->getAnnotation('Conflicts')->value;
		if($target->hasAnnotation($value))
		{
			throw new ConflictException(sprintf('Annotation `%s` cannot be used together with `%s` in `%s`', $reflection->name, $value, ReflectionName::createName($target)));
		}
	}
}
