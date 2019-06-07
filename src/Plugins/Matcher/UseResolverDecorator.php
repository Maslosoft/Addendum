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
		$reflection = ReflectionHelper::getReflectionClass($matcher->getPlugins()->reflection, $matcher);
		$resolved = UseResolver::resolve($reflection, $value);
		if (ClassChecker::exists($resolved))
		{
			$value = $resolved;
		}
	}

}
