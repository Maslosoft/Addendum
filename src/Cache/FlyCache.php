<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Cache;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 * Flyweight accessor for meta cache
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FlyCache
{

	/**
	 * Instances of meta cache
	 * @var MetaCache[]
	 */
	private static $_instances = [];

	/**
	 * Get flyweight instance of meta cache.
	 * This is based on meta class and addendum instance id.
	 * @param string $metaClass
	 * @param AnnotatedInterface $component
	 * @param MetaOptions $options
	 * @return MetaCache
	 */
	public static function instance($metaClass = null, AnnotatedInterface $component = null, MetaOptions $options = null)
	{
		if (empty($options))
		{
			$instanceId = Addendum::DefaultInstanceId;
		}
		else
		{
			$instanceId = $options->instanceId;
		}
		$key = sprintf('%s@%s', $metaClass, $instanceId);

		if (empty(self::$_instances[$key]))
		{
			self::$_instances[$key] = new MetaCache($metaClass, $component, $options);
		}
		self::$_instances[$key]->setComponent($component);
		self::$_instances[$key]->setOptions($options);
		return self::$_instances[$key];
	}

}
