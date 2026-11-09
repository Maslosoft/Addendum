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

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Exceptions\ClassNotFoundException;
use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Matcher\Helpers\Processor;
use Maslosoft\Addendum\Reflection\ReflectionFile;
use Maslosoft\Addendum\Utilities\RawClassConstantResolver;
use Maslosoft\Addendum\Utilities\ClassChecker;

class StaticConstantMatcher extends RegexMatcher implements MatcherInterface
{

	public function __construct()
	{
		parent::__construct('([\w\\\]+::\w+)');
	}

	protected function process($matches)
	{
		$value = $matches[1];
		$parts = explode('::', $value);
		$className = $parts[0];
		$constName = $parts[1];
		$className = Processor::process($this, $className);
		$value = sprintf('%s::%s', $className, $constName);
		$value = Processor::process($this, $value);
		// PHP resolves ::class without loading the referenced class.
		if ($constName === 'class' && $value === $className)
		{
			return $value;
		}

		$reflection = $this->getPlugins()->reflection;
		if ($reflection instanceof ReflectionFile && $className === $reflection->getName()
			&& !class_exists($className, false) && !trait_exists($className, false) && !interface_exists($className, false))
		{
			// rawAnnotate() must not include or autoload the inspected PHP file.
			return RawClassConstantResolver::resolve($reflection, $constName);
		}

		if ($className !== 'self' && $className !== 'static')
		{
			if (!ClassChecker::exists($className))
			{
				throw new ClassNotFoundException("Class $className not found while parsing annotations");
			}
		}


		if (!defined($value))
		{
			return false;
		}
		return constant($value);
	}

}
