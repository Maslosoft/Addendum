<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Plugins\Matcher;

use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherDecoratorInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\ReflectionHelper;
use Maslosoft\Addendum\Utilities\UseResolver;

/**
 * UseResolver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UseResolverDecorator implements MatcherDecoratorInterface
{

	public function decorate(MatcherInterface $matcher, &$value)
	{
		$reflection = ReflectionHelper::getReflectionClass($matcher->getPlugins()->reflection);
		$resolved = UseResolver::resolve($reflection, $value);
		if (ClassChecker::exists($resolved))
		{
			$value = $resolved;
		}
	}

}
