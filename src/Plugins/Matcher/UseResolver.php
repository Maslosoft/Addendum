<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Plugins\Matcher;

use Exception;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\IMatcherDecorator;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * UseResolver
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class UseResolver implements IMatcherDecorator
{

	public function decorate(IMatcher $matcher, &$value)
	{
		$reflection = $this->getReflectionClass($matcher);
		$use = (new DocComment())->forClass($reflection)['use'];
		/**
		 * TODO Log not found classes
		 */
	}

	/**
	 * Get reflection class.
	 * @return ReflectionAnnotatedClass
	 * @throws Exception
	 */
	public function getReflectionClass($matcher)
	{
		$reflection = $matcher->getPlugins()->reflection;
		if (null === $reflection)
		{
			throw new Exception(sprintf('No reflection class for matcher `%s`', get_class($this)));
		}
		if ($reflection instanceof ReflectionAnnotatedMethod)
		{
			return $reflection->getDeclaringClass();
		}
		if ($reflection instanceof ReflectionAnnotatedProperty)
		{
			return $reflection->getDeclaringClass();
		}
		return $reflection;
	}

}
