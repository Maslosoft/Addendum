<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use ReflectionClass;

/**
 * UseResolver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UseResolver
{

	/**
	 * Resolve class alias
	 * @param ReflectionClass $reflection
	 * @param string $className
	 * @return string
	 */
	public static function resolve(ReflectionClass $reflection, $className)
	{
		$docs = (new DocComment())->forClass($reflection);
		$use = $docs['use'];
		$ns = $docs['namespace'];
		$aliases = $docs['useAliases'];

		// This is for same namespaced class as current class
		$aliases[$ns . '\\' . $className] = $className;

		if (in_array($className, $use))
		{
			return $className;
		}
		foreach ($use as $useClause)
		{
			$patternClass = preg_quote($className);
			$pattern = "~\\\\$patternClass$~";
			if (preg_match($pattern, $useClause))
			{
				return $useClause;
			}
		}
		foreach ($aliases as $useClause => $alias)
		{
			if ($className == $alias)
			{
				NameNormalizer::normalize($useClause, false);
				return $useClause;
			}
		}
		return $className;
	}

}
