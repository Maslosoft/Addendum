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

use function array_key_exists;
use function array_unique;
use Exception;
use function get_class;
use function get_parent_class;
use function is_object;
use ReflectionClass;
use function sort;
use function str_contains;

/**
 * ClassChecker
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassChecker
{

	/**
	 * Array with existent classes
	 * @var string[]
	 */
	private static array $_exists = [];

	/**
	 * Partials cache
	 * @var array
	 */
	private static array $partials = [];

	/**
	 * Check whenever class is anonymous.
	 * @param string|object $class
	 * @return bool True if class is anonymous
	 */
	public static function isAnonymous($class): bool
	{
		if(is_object($class))
		{
			$class = get_class($class);
		}
		return str_contains($class, '@anonymous');
	}

	/**
	 * Check whenever class or trait or interface exists.
	 * It does autoload if needed.
	 * @param string|object $class
	 * @return bool True if class or trait or interface exists
	 */
	public static function exists($class): bool
	{
		if(is_object($class))
		{
			$class = get_class($class);
		}
		if (Blacklister::ignores($class))
		{
			return false;
		}
		if (self::isConfirmed($class))
		{
			return true;
		}
		try
		{
			if (@class_exists($class))
			{
				return self::confirm($class);
			}
		}
		catch (Exception $ex)
		{
			// Some class loaders throw exception if class not found
		}
		try
		{
			if (@trait_exists($class))
			{
				return self::confirm($class);
			}
		}
		catch (Exception $ex)
		{
			// Some class loaders throw exception if class not found
		}
		try
		{
			if (@interface_exists($class))
			{
				return self::confirm($class);
			}
		}
		catch (Exception $ex)
		{
			// Some class loaders throw exception if class not found
		}
		Blacklister::ignore($class);
		return false;
	}

	/**
	 * Get class/interface/trait names from which class is composed.
	 *
	 * @param string $className
	 * @return array
	 */
	public static function getPartials($className)
	{
		if (array_key_exists($className, self::$partials))
		{
			return self::$partials[$className];
		}
		if(!self::exists($className))
		{
			self::$partials[$className] = [];
			return [];
		}
		$partials = [];
		// Iterate over traits
		foreach ((new ReflectionClass($className))->getTraitNames() as $trait)
		{
			$partials[] = $trait;
			foreach(self::getPartials($trait) as $traitPart)
			{
				$partials[] = $traitPart;
			}
		}

		// Iterate over interfaces to get partials
		foreach ((new ReflectionClass($className))->getInterfaceNames() as $interface)
		{
			$partials[] = $interface;
		}

		// Iterate over parent classes
		do
		{
			$partials[] = $className;

			// Iterate over traits of parent class
			foreach ((new ReflectionClass($className))->getTraitNames() as $trait)
			{
				$partials[] = $trait;
			}

			// Iterate over interfaces of parent class
			foreach ((new ReflectionClass($className))->getInterfaceNames() as $interface)
			{
				$partials[] = $interface;
			}

		}
		while (($className = get_parent_class($className)) !== false);
		$partials = array_unique($partials);
		sort($partials);
		self::$partials[$className] = $partials;
		return $partials;
	}

	private static function isConfirmed($class): bool
	{
		return isset(self::$_exists[$class]);
	}

	private static function confirm($class): bool
	{
		self::$_exists[$class] = true;
		return true;
	}

}
