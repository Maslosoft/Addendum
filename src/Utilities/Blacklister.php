<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
