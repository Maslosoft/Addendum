<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.12.17
 * Time: 10:19
 */

namespace Maslosoft\Addendum\Interfaces\Plugins\Matcher;


interface MatcherClassNotFoundHandlerInterface
{
	public function shouldSkip($className);
}