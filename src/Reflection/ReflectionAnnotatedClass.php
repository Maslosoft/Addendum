<?php
namespace Maslosoft\Addendum\Reflection;

use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Utilities\ConflictChecker;
use ReflectionClass;

class ReflectionAnnotatedClass extends ReflectionClass
{
	/**
	 * Annotations collection
	 * @var AnnotationsCollection
	 */
	private $annotations = null;

	public function __construct($class)
	{
		parent::__construct($class);
		$this->annotations = (new Builder)->build($this);

		ConflictChecker::check($this, $this->annotations);
	}

	public function hasAnnotation($class)
	{
		return $this->annotations->hasAnnotation($class);
	}

	public function getAnnotation($annotation)
	{
		return $this->annotations->getAnnotation($annotation);
	}

	public function getAnnotations()
	{
		return $this->annotations->getAnnotations();
	}

	public function getAllAnnotations($restriction = false)
	{
		return $this->annotations->getAllAnnotations($restriction);
	}

	public function getConstructor()
	{
		return $this->createReflectionAnnotatedMethod(parent::getConstructor());
	}

	public function getMethod($name)
	{
		return $this->createReflectionAnnotatedMethod(parent::getMethod($name));
	}

	public function getMethods($filter = -1)
	{
		$result = [];
		foreach(parent::getMethods($filter) as $method)
		{
			$result[] = $this->createReflectionAnnotatedMethod($method);
		}
		return $result;
	}

	public function getProperty($name)
	{
		return $this->createReflectionAnnotatedProperty(parent::getProperty($name));
	}

	public function getProperties($filter = -1)
	{
		$result = [];
		foreach(parent::getProperties($filter) as $property)
		{
			$result[] = $this->createReflectionAnnotatedProperty($property);
		}
		return $result;
	}

	public function getInterfaces()
	{
		$result = [];
		foreach(parent::getInterfaces() as $interface)
		{
			$result[] = $this->createReflectionAnnotatedClass($interface);
		}
		return $result;
	}

	public function getParentClass()
	{
		$class = parent::getParentClass();
		return $this->createReflectionAnnotatedClass($class);
	}

	private function createReflectionAnnotatedClass($class)
	{
		return ($class !== false) ? new ReflectionAnnotatedClass($class->name) : false;
	}

	private function createReflectionAnnotatedMethod($method)
	{
		return ($method !== null) ? new ReflectionAnnotatedMethod($this->name, $method->name) : null;
	}

	private function createReflectionAnnotatedProperty($property)
	{
		return ($property !== null) ? new ReflectionAnnotatedProperty($this->name, $property->name) : null;
	}
}