<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * IgnoredChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IgnoredChecker
{

	/**
	 * Check if entity is ignored
	 * @param ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $target
	 * @return bool
	 */
	public static function check($target)
	{
		if (!$target->hasAnnotation('Ignored'))
		{
			return false;
		}

		$value = $target->getAnnotation('Ignored')->value;
		if (false === $value)
		{
			return false;
		}
		return true;
	}

}
