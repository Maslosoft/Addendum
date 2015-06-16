<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
