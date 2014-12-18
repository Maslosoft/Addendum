<?php

namespace Maslosoft\Addendum\Builder;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Annotations\ConflictsAnnotation;
use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Collections\AnnotationsCollection;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Yii;

/**
 * @Label("Annotations builder")
 */
class Builder
{

	private static $cache = array();

	public function build($targetReflection)
	{
		$data = $this->parse($targetReflection);
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

	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		$class = ucfirst($class) . "Annotation";

		// If namespaces are empty assume global namespace
		$fqn = $this->normalizeFqn('\\', $class);
		foreach (Yii::app()->addendum->namespaces as $ns)
		{
			$fqn = $this->normalizeFqn($ns, $class);
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
					break;
				}
			}
			catch (\Exception $e)
			{
				
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
		catch (\Exception $e)
		{
			return false;
		}
		$resolvedClass = Addendum::resolveClassName($fqn);
		if (is_subclass_of($resolvedClass, Annotation::class) || $resolvedClass == Annotation::class)
		{
			$annotationReflection = new ReflectionClass($resolvedClass);
			return $annotationReflection->newInstance($parameters, $targetReflection);
		}
		return false;
	}

	private function normalizeFqn($ns, $class)
	{
		return preg_replace('~\\\+~', '\\', "\\$ns\\$class");
	}

	private function parse($reflection)
	{
		$key = $this->createName($reflection);
		if (!isset(self::$cache[$key]))
		{
			$parser = new AnnotationsMatcher;
			$parser->matches($this->getDocComment($reflection), $data);
			self::$cache[$key] = $data;
		}
		return self::$cache[$key];
	}

	private function createName($target)
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

	protected function getDocComment($reflection)
	{
		return Addendum::getDocComment($reflection);
	}

	public static function clearCache()
	{
		self::$cache = array();
	}

}
