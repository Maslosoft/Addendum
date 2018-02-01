<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 01.02.18
 * Time: 15:58
 */

namespace Maslosoft\Addendum\Plugins\Matcher;


use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherDecoratorInterface;

class MagicConstantDecorator implements MatcherDecoratorInterface
{

	public function decorate(MatcherInterface $matcher, &$value)
	{
		if(strpos($value, '::class') !== false)
		{
			$parts = explode('::', $value);
			if(count($parts) !== 2)
			{
				return;
			}

			$value = $parts[0];
		}
	}
}