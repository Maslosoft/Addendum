<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Plugins\Matcher;

use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\IMatcherDecorator;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\ReflectionHelper;
use Maslosoft\Addendum\Utilities\UseResolver;

/**
 * UseResolver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UseResolverDecorator implements IMatcherDecorator
{

	public function decorate(IMatcher $matcher, &$value)
	{
		$reflection = ReflectionHelper::getReflectionClass($matcher->getPlugins()->reflection);
		$resolved = UseResolver::resolve($reflection, $value);
		if (ClassChecker::exists($resolved))
		{
			$value = $resolved;
		}
	}

}
