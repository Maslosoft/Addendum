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
	 * @var ClassCache[]
	 */
	private static $_instances = [];

	/**
	 * Get flyweight instance of meta cache.
	 * This is based on meta class and addendum instance id.
	 * @param string $metaClass
	 * @param string|object|AnnotatedInterface $component
	 * @param MetaOptions $options
	 * @return ClassCache
	 */
	public static function instance($metaClass = null, $component = null, ?MetaOptions $options = null)
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
			self::$_instances[$key] = new ClassCache($metaClass, $component, $options);
		}
		self::$_instances[$key]->setOptions($options);
		return self::$_instances[$key];
	}

}
