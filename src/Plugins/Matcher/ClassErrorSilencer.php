<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.12.17
 * Time: 09:46
 */

namespace Maslosoft\Addendum\Plugins\Matcher;


use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherClassNotFoundHandlerInterface;

class ClassErrorSilencer implements MatcherClassNotFoundHandlerInterface
{
	public $classes = [];
	public function shouldSkip($className)
	{
		return in_array($className, $this->classes);
	}

}