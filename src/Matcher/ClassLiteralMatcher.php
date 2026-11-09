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
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherClassNotFoundHandlerInterface;
use Maslosoft\Addendum\Matcher\Helpers\Processor;
use Maslosoft\Addendum\Matcher\Traits\PluginsTrait;
use Maslosoft\Addendum\Reflection\ReflectionFile;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\ReflectionName;
use Maslosoft\Gazebo\PluginFactory;

/**
 * ClassLiteralMatcher
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ClassLiteralMatcher implements MatcherInterface
{

	use PluginsTrait;

	protected function process($matches)
	{
		return Processor::process($this, $matches[1]);
	}

	public function matches($string, &$value)
	{
		$matches = [];
		$regex = '(static|self|[A-Z\\\][a-zA-Z0-9_\\\]+)';
		if (preg_match("~^{$regex}~", $string, $matches))
		{
			// Leave ClassName::CONSTANT (including self/static) to StaticConstantMatcher.
			if (substr($string, strlen($matches[0]), 2) === '::')
			{
				$value = false;
				return false;
			}
			$value = $this->process($matches);
			// Raw file reflections intentionally do not load their declaring class.
			$rawSelf = $this->getPlugins()->reflection instanceof ReflectionFile && in_array($matches[0], ['self', 'static'], true);
			if (!$rawSelf && !ClassChecker::exists($value))
			{
				// Might be constant...
				if (!defined($matches[0]))
				{
					$name = ReflectionName::createName($this->getPlugins()->reflection);

					$params = [
						$matches[0],
						$name,
						$string
					];
					$plugins = $this->getPlugins()->addendum->plugins->matcher;

					$instances = PluginFactory::fly()->create($plugins, $this, MatcherClassNotFoundHandlerInterface::class);

					/* @var $instances MatcherClassNotFoundHandlerInterface[] */

					foreach($instances as $ignorer)
					{
						if($ignorer->shouldSkip($matches[0]))
						{
							return false;
						}
					}
					throw new ClassNotFoundException(vsprintf("Could not find class %s, when processing annotations on %s, near %s", $params));
				}
				return false;
			}
			return strlen($matches[0]);
		}
		$value = false;
		return false;
	}

}
