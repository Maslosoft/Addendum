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

namespace Maslosoft\Addendum\Matcher\Helpers;

use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherDecoratorInterface;
use Maslosoft\Gazebo\PluginFactory;

/**
 * Decorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Decorator
{

	public static function decorate(MatcherInterface $matcher, $value)
	{
		$factory = new PluginFactory();
		$config = $matcher->getPlugins()->addendum->plugins->matcher;
		$decorators = $factory->instance($config, $matcher, [
			MatcherDecoratorInterface::class
		]);
		foreach ($decorators as $decorator)
		{
			/* @var $decorator MatcherDecoratorInterface  */
			$decorator->decorate($matcher, $value);
		}
		return $value;
	}

}
