<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 01.02.18
 * Time: 15:28
 */

namespace Maslosoft\Addendum\Plugins\Matcher;


use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherDecoratorInterface;
use Maslosoft\Addendum\Utilities\ReflectionHelper;

class SelfKeywordDecorator implements MatcherDecoratorInterface
{

	public function decorate(MatcherInterface $matcher, &$value)
	{
		if($value === 'self' || $value === 'static')
		{
			$reflection = ReflectionHelper::getReflectionClass($matcher->getPlugins()->reflection, $matcher);
			$value = $reflection->name;
		}
	}
}