<?php

namespace Maslosoft\Addendum\Builder;

use Exception;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Yii;

/**
 * @Label("Annotations builder")
 */
class Builder
{

	/**
	 * Cached values of parsing
	 * @var string[][][]
	 */
	private static $_cache = array();

	/**
	 * Build annotations collection
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @return AnnotationsCollection
	 */
	public function build($targetReflection)
	{
		$data = $this->_parse($targetReflection);
		$annotations = array();
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

	/**
	 * Create new instance of annotation
	 * @param string $class
	 * @param mixed[] $parameters
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
	 * @return boolean|object
	 */
	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		$class = ucfirst($class) . "Annotation";

		// If namespaces are empty assume global namespace
		$fqn = $this->_normalizeFqn('\\', $class);
		foreach (Yii::app()->addendum->namespaces as $ns)
		{
			$fqn = $this->_normalizeFqn($ns, $class);
			if (Addendum::ignores($fqn))
			{
				return false;
			}
			try
			{
				if (!class_exists($fqn))
				{
					Yii::trace(sprintf('Annotation class %s not found, ignoring', $fqn), 'annotation');
					Addendum::ignore($fqn);
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

		try
		{
			if (!class_exists($fqn))
			{
				Yii::trace(sprintf('Annotation class %s not found, ignoring', $fqn), 'annotation');
				Addendum::ignore($fqn);
				return false;
			}
		}
		catch (Exception $e)
		{
			// Ignore autoload errors and return false
			Addendum::ignore($fqn);
			return false;
		}
		$resolvedClass = Addendum::resolveClassName($fqn);
		if ((new ReflectionClass($resolvedClass))->implementsInterface(IAnnotation::class) || $resolvedClass == IAnnotation::class)
		{
			$annotationReflection = new ReflectionClass($resolvedClass);
			return $annotationReflection->newInstance($parameters, $targetReflection);
		}
		return false;
	}

	/**
	 * Normalize class name and namespace to proper fully qualified name
	 * @param string $ns
	 * @param string $class
	 * @return string
	 */
	private function _normalizeFqn($ns, $class)
	{
		return preg_replace('~\\\+~', '\\', "\\$ns\\$class");
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $reflection
	 * @return mixed[]
	 */
	private function _parse($reflection)
	{
		$key = $this->_createName($reflection);
		if (!isset(self::$_cache[$key]))
		{
			$parser = new AnnotationsMatcher;
			$parser->matches($this->getDocComment($reflection), $data);
			self::$_cache[$key] = $data;
		}
		return self::$_cache[$key];
	}

	/**
	 * Create class name
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $target
	 * @return string
	 */
	private function _createName($target)
	{
		if ($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->name . '::' . $target->name;
		}
		elseif ($target instanceof ReflectionProperty)
		{
			return $target->getDeclaringClass()->name . '::$' . $target->name;
		}
		else
		{
			return $target->name;
		}
	}

	/**
	 * Get doc comment
	 * @param ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty $targetReflection
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
		self::$_cache = array();
	}

}
