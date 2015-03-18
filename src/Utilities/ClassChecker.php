<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
	 * Array with ignored classes
	 * @var string[]
	 */
	private static $_ignore = [];

	/**
	 * Array with existent classes
	 * @var string[]
	 */
	private static $_exists = [];

	/**
	 * Check whenever class or trait or interface exists.
	 * @param string $class
	 * @return bool Returns true if class exists
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
			if (class_exists($class))
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
			if (trait_exists($class))
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
			if (interface_exists($class))
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
