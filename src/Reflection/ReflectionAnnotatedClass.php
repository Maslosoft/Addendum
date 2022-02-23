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

namespace Maslosoft\Addendum\Reflection;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Interfaces\AnnotatedReflectorInterface;
use Maslosoft\Addendum\Utilities\ConflictChecker;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class ReflectionAnnotatedClass extends ReflectionClass implements AnnotatedReflectorInterface
{

	/**
	 * Annotations collection
	 * @var AnnotationsCollection
	 */
	private $annotations = null;

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $addendum = null;

	public function __construct($class, Addendum $addendum = null)
	{
		parent::__construct($class);
		$this->annotations = (new Builder($addendum))->build($this);
		$this->addendum = $addendum;
		ConflictChecker::check($this, $this->annotations);
	}

	public function hasAnnotation($class): bool
	{
		return $this->annotations->hasAnnotation($class);
	}

	public function getAnnotation($annotation)
	{
		return $this->annotations->getAnnotation($annotation);
	}

	public function getAnnotations(): array
	{
		return $this->annotations->getAnnotations();
	}

	public function getAllAnnotations($restriction = false): array
	{
		return $this->annotations->getAllAnnotations($restriction);
	}

	public function getConstructor(): ?ReflectionMethod
	{
		return $this->createReflectionAnnotatedMethod(parent::getConstructor());
	}

	#[\ReturnTypeWillChange]
	public function getMethod($name): ?ReflectionMethod
	{
		return $this->createReflectionAnnotatedMethod(parent::getMethod($name));
	}

	#[\ReturnTypeWillChange]
	public function getMethods($filter = -1): array
	{
		$result = [];
		foreach (parent::getMethods($filter) as $method)
		{
			$result[] = $this->createReflectionAnnotatedMethod($method);
		}
		return $result;
	}

	#[\ReturnTypeWillChange]
	public function getProperty($name): ?ReflectionProperty
	{
		return $this->createReflectionAnnotatedProperty(parent::getProperty($name));
	}

	#[\ReturnTypeWillChange]
	public function getProperties($filter = -1): array
	{
		$result = [];
		foreach (parent::getProperties($filter) as $property)
		{
			$result[] = $this->createReflectionAnnotatedProperty($property);
		}
		return $result;
	}

	#[\ReturnTypeWillChange]
	public function getInterfaces(): array
	{
		$result = [];
		foreach (parent::getInterfaces() as $interface)
		{
			$result[] = $this->createReflectionAnnotatedClass($interface);
		}
		return $result;
	}

	#[\ReturnTypeWillChange]
	public function getParentClass()
	{
		$class = parent::getParentClass();
		return $this->createReflectionAnnotatedClass($class);
	}

	private function createReflectionAnnotatedClass($class)
	{
		return ($class !== false) ? new ReflectionAnnotatedClass($class->name, $this->addendum) : false;
	}

	private function createReflectionAnnotatedMethod($method): ?ReflectionAnnotatedMethod
	{
		return ($method !== null) ? new ReflectionAnnotatedMethod($this->name, $method->name, $this->addendum) : null;
	}

	private function createReflectionAnnotatedProperty($property): ?ReflectionAnnotatedProperty
	{
		return ($property !== null) ? new ReflectionAnnotatedProperty($this->name, $property->name, $this->addendum) : null;
	}

}
