<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Builder;

use Exception;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\Blacklister;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use Maslosoft\Addendum\Utilities\ReflectionName;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 * @Label("Annotations builder")
 */
class Builder
{

	/**
	 * Cached values of parsing
	 * @var string[][][]
	 */
	private static $cache = [];

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $addendum = null;

	public function __construct(Addendum $addendum = null)
	{
		$this->addendum = $addendum? : new Addendum();
	}

	/**
	 * Build annotations collection
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @return AnnotationsCollection
	 */
	public function build($targetReflection)
	{
		$annotations = [];


		// Decide where from take traits and base classes.
		// Either from class if it's being processed reflection class
		// or from declaring class if it's being processed for properties and
		// methods
		if ($targetReflection instanceof ReflectionClass)
		{
			$targetClass = $targetReflection;
		}
		else
		{
			$targetClass = $targetReflection->getDeclaringClass();
		}
		$traits = $targetClass->getTraits();

		// Get annotations from parent classes
		$parentsData = [];
		$targetParent = $targetClass;
		while ($targetParent = $targetParent->getParentClass())
		{
			$parentData = $this->getDataFor($targetReflection, $targetParent->name);
			if (false === $parentData || empty($parentData))
			{
				continue;
			}
			$parentsData = array_merge($parentsData, $parentData);
		}

		// Get annotations from traits
		$traitsData = [];
		foreach ($traits as $trait)
		{
			$traitData = $this->getDataFor($targetReflection, $trait->name);
			if (false === $traitData)
			{
				continue;
			}
			$traitsData = array_merge($traitsData, $traitData);
		}

		// Data from class
		$data = $this->parse($targetReflection);

		// Merge data from traits
		$data = array_merge($parentsData, $traitsData, $data);

		// Get annotations from current entity
		foreach ($data as $class => $parameters)
		{
			foreach ($parameters as $params)
			{
				$annotation = $this->instantiateAnnotation($class, $params, $targetReflection);
				if ($annotation !== false)
				{
					$annotations[$class][] = $annotation;
				}
			}
		}
		return new AnnotationsCollection($annotations);
	}

	private function buildOne($targetReflection)
	{
		
	}

	private function getDataFor($targetReflection, $name)
	{
		$target = new ReflectionAnnotatedClass($name, $this->addendum);
		$annotations = null;

		// Try to get annotations from entity, be it method, property or trait itself
		switch (true)
		{
			case $targetReflection instanceof ReflectionProperty && $target->hasProperty($targetReflection->name):
				$annotations = new ReflectionAnnotatedProperty($target->name, $targetReflection->name, $this->addendum);
				break;
			case $targetReflection instanceof ReflectionMethod && $target->hasMethod($targetReflection->name):
				$annotations = new ReflectionAnnotatedMethod($target->name, $targetReflection->name, $this->addendum);
				break;
			case $targetReflection instanceof ReflectionClass:
				$annotations = $target;
				break;
		}

		// Does not have property or method
		if (null === $annotations)
		{
			return false;
		}

		// Data from traits
		return $this->parse($annotations);
	}

	/**
	 * Create new instance of annotation
	 * @param string $class
	 * @param mixed[] $parameters
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|bool $targetReflection
	 * @return boolean|object
	 */
	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		$class = ucfirst($class) . "Annotation";

		// If namespaces are empty assume global namespace
		$fqn = $this->normalizeFqn('\\', $class);
		foreach ($this->addendum->namespaces as $ns)
		{
			$fqn = $this->normalizeFqn($ns, $class);
			if (Blacklister::ignores($fqn))
			{
				continue;
			}
			try
			{
				if (!ClassChecker::exists($fqn))
				{
					$this->addendum->getLogger()->debug('Annotation class `{fqn}` not found, ignoring', ['fqn' => $fqn]);
					Blacklister::ignore($fqn);
				}
				else
				{
					// Class exists, exit loop
					break;
				}
			}
			catch (Exception $e)
			{
				// Ignore class autoloading errors
			}
		}
		if (Blacklister::ignores($fqn))
		{
			return false;
		}
		try
		{
			// NOTE: @ need to be used here or php might complain
			if (@!class_exists($fqn))
			{
				$this->addendum->getLogger()->debug('Annotation class `{fqn}` not found, ignoring', ['fqn' => $fqn]);
				Blacklister::ignore($fqn);
				return false;
			}
		}
		catch (Exception $e)
		{
			// Ignore autoload errors and return false
			Blacklister::ignore($fqn);
			return false;
		}
		$resolvedClass = Addendum::resolveClassName($fqn);
		if ((new ReflectionClass($resolvedClass))->implementsInterface(AnnotationInterface::class) || $resolvedClass == AnnotationInterface::class)
		{
			return new $resolvedClass($parameters, $targetReflection);
		}
		return false;
	}

	/**
	 * Normalize class name and namespace to proper fully qualified name
	 * @param string $ns
	 * @param string $class
	 * @return string
	 */
	private function normalizeFqn($ns, $class)
	{
		$fqn = "\\$ns\\$class";
		NameNormalizer::normalize($fqn);
		return $fqn;
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $reflection
	 * @return mixed[]
	 */
	private function parse($reflection)
	{
		$key = ReflectionName::createName($reflection);
		if (!isset(self::$cache[$key]))
		{
			$parser = new AnnotationsMatcher;
			$data = [];
			$parser->setPlugins(new MatcherConfig([
				'addendum' => $this->addendum,
				'reflection' => $reflection
			]));
			$parser->matches($this->getDocComment($reflection), $data);
			self::$cache[$key] = $data;
		}
		return self::$cache[$key];
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $reflection
	 * @return mixed[]
	 */
	protected function getDocComment($reflection)
	{
		return Addendum::getDocComment($reflection);
	}

	/**
	 * Clear local parsing cache
	 */
	public static function clearCache()
	{
		self::$cache = [];
	}

}
