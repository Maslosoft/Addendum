<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
