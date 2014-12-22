<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
