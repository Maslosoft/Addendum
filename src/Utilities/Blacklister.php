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

/**
 * Blacklist classes
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Blacklister
{

	/**
	 * Array with ignored classes.
	 * Class name is key
	 * @var bool[]
	 */
	private static $_ignore;

	/**
	 * Reset ignore list
	 */
	public static function reset()
	{
		self::$_ignore = [];
	}

	/**
	 * Check if class is on ignore list
	 * @param string $class
	 * @return bool
	 */
	public static function ignores($class)
	{
		return isset(self::$_ignore[$class]);
	}

	/**
	 * Add class to ignore list
	 * @param string $class
	 */
	public static function ignore($class)
	{
		self::$_ignore[$class] = true;
	}

}
