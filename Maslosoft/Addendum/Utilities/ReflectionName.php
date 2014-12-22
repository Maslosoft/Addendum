<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
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
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|bool $target
	 * @return string
	 */
	public static function createName($target)
	{
		if(!$target)
		{
			return 'unknown';
		}
		if ($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->name . '::' . $target->name;
		}
		elseif ($target instanceof ReflectionProperty)
		{
			return $target->getDeclaringClass()->name . '::$' . $target->name;
		}
		else
		{
			return $target->name;
		}
	}

}
