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

use Exception;

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
	private static $_exists = [];

	/**
	 * Check whenever class is anonymous.
	 * @param string $class
	 * @return bool True if class is anonymous
	 */
	public static function isAnonymous($class)
	{
		return strpos($class, 'class@anonymous') !== false;
	}

	/**
	 * Check whenever class or trait or interface exists.
	 * It does autoload if needed.
	 * @param string $class
	 * @return bool True if class or trait or interface exists
	 */
	public static function exists($class)
	{
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

	private static function isConfirmed($class)
	{
		return isset(self::$_exists[$class]);
	}

	private static function confirm($class)
	{
		self::$_exists[$class] = true;
		return true;
	}

}
