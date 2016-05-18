<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
	 * It does not ensure that file really contains anniotations.
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
