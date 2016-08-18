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
use Maslosoft\Addendum\Cache\BuildOneCache;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Helpers\CoarseChecker;
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
use Reflector;

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

	/**
	 * One entity build cache
	 * @var BuildOneCache
	 */
	private $buildCache = null;

	public function __construct(Addendum $addendum = null)
	{
		$this->addendum = $addendum ?: new Addendum();
		$this->buildCache = new BuildOneCache(static::class, null, $this->addendum);
	}

	/**
	 * Build annotations collection
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @return AnnotationsCollection
	 */
	public function build($targetReflection)
	{
		$annotations = [];

		$data = $this->buildOne($targetReflection);

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
		if (empty($targetReflection))
		{
			return [];
		}
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

		// Check if currently reflected component is cached
		$this->buildCache->setComponent(ReflectionName::createName($targetReflection));
		$cached = $this->buildCache->get();

		// Check if currently reflected component *class* is cached
		$this->buildCache->setComponent(ReflectionName::createName($targetClass));
		$cachedClass = $this->buildCache->get();

		// Cache is valid only if class cache is valid,
		// this is required for Conflicts and Target checks
		if (false !== $cached && false !== $cachedClass)
		{
			return $cached;
		}
		$traits = $targetClass->getTraits();

		// Get annotations from interfaces
		$interfacesData = [];
		foreach ($targetClass->getInterfaces() as $targetInterface)
		{
			// Recurse as interface might extend from other interfaces
			$interfaceData = $this->buildOne($this->getRelated($targetReflection, $targetInterface));
			if (empty($interfaceData))
			{
				continue;
			}
			$interfacesData = array_merge($interfacesData, $interfaceData);
		}

		// Get annotations from parent classes
		$parentData = [];
		$targetParent = $targetClass->getParentClass();
		if (!empty($targetParent))
		{
			// Recurse if has parent class, as it might have traits 
			// or interfaces too
			$parentData = $this->buildOne($this->getRelated($targetReflection, $targetParent));
		}

		// Get annotations from traits
		$traitsData = [];
		foreach ($traits as $trait)
		{
			// Need to recurse here, as trait might have traits
			$traitData = $this->buildOne($this->getRelated($targetReflection, $trait));
			if (false === $traitData || empty($traitData) || !is_array($traitData))
			{
				continue;
			}
			$traitsData = array_merge($traitsData, $traitData);
		}

		// Merge data from traits etc.
		// Data from class
		$data = array_merge($interfacesData, $parentData, $traitsData, $this->parse($targetReflection));

		$this->buildCache->setComponent(ReflectionName::createName($targetReflection));
		$this->buildCache->set($data);
		return $data;
	}

	/**
	 * Get reflection for related target reflection. 
	 * Will return class, property or method reflection from related reflection, 
	 * based on type of target reflection.
	 *
	 * Will return false target reflection id for method or property, and
	 * method or property does not exists on related reflection.
	 *
	 * @param Reflector $targetReflection
	 * @param ReflectionClass $relatedReflection
	 * @return ReflectionClass|ReflectionProperty|ReflectionMethod|bool
	 */
	private function getRelated(Reflector $targetReflection, ReflectionClass $relatedReflection)
	{
		switch (true)
		{
			case $targetReflection instanceof ReflectionClass:
				return $relatedReflection;
			case $targetReflection instanceof ReflectionProperty && $relatedReflection->hasProperty($targetReflection->name):
				return $relatedReflection->getProperty($targetReflection->name);
			case $targetReflection instanceof ReflectionMethod && $relatedReflection->hasMethod($targetReflection->name):
				return $relatedReflection->getMethod($targetReflection->name);
		}
		return false;
	}

	/**
	 *
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @param string $name
	 * @return boolean|mixed
	 */
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

		// Data from target
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
			// NOTE: was class_exists here, however should be safe to use ClassChecker::exists here
			if (!ClassChecker::exists($fqn))
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
		$key = sprintf('%s@%s', $this->addendum->getInstanceId(), ReflectionName::createName($reflection));
		if (!isset(self::$cache[$key]))
		{
			//
			if (!CoarseChecker::mightHaveAnnotations($reflection))
			{
				self::$cache[$key] = [];
				return self::$cache[$key];
			}
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
