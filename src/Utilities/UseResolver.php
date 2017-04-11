<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;

/**
 * UseResolver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UseResolver
{

	/**
	 * Resolve class alias
	 * @param Reflector $reflection
	 * @param string $className
	 * @return string
	 */
	public static function resolve(Reflector $reflection, $className)
	{
		if ($reflection instanceof ReflectionProperty || $reflection instanceof ReflectionMethod)
		{
			$reflection = $reflection->getDeclaringClass();
		}
		$docs = (new DocComment())->forClass($reflection);
		$use = $docs['use'];
		$ns = $docs['namespace'];
		$aliases = $docs['useAliases'];

		// Resolve to itself with keywords
		if ($className === 'self' || $className === 'static')
		{
			$fqn = $ns . '\\' . $docs['className'];
			return $fqn;
		}

		// This is for same namespaced class as current class
		if (strpos($className, '\\') === false)
		{
			$aliases[$ns . '\\' . $className] = $className;
		}

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
