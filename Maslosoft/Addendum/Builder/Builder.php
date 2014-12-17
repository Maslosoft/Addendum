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
		foreach($data as $class => $parameters)
		{
			foreach($parameters as $params)
			{
				$annotation = $this->instantiateAnnotation($class, $params, $targetReflection);
				if($annotation !== false)
				{
					$annotations[$class][] = $annotation;
				}
			}
		}
		return new AnnotationsCollection($annotations);
	}

	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		/** @todo This should loop thru user-defined list of paths and annotatios
		 * @todo Allow namespaced annotations ie. @ext.example.Annotation or @ext\example\Annotation
		 * and try to Yii::import('path.to.%class%Annotation');
		 * OR better move this to EAddendum::resolveClassName
		 */
		if($class == 'Target')
		{
			$class = TargetAnnotation::class;
		}
		if($class == 'Conflicts')
		{
			$class = ConflictsAnnotation::class;
		}
		if(strstr($class, '\\'))
		{
			// var_dump("Namespaced: $class");
		}
		else
		{
			$class = ucfirst($class) . "Annotation";
		}
		if(Addendum::ignores($class))
		{
			return false;
		}
		if(@!class_exists($class))
		{
			Yii::trace(sprintf('Annotation class %s not found, ignoring', $class), 'annotation');
			Addendum::ignore($class);
			return false;
		}
		$resolvedClass = Addendum::resolveClassName($class);
		if(is_subclass_of($resolvedClass, Annotation::class) || $resolvedClass == Annotation::class)
		{
			$annotationReflection = new ReflectionClass($resolvedClass);
			return $annotationReflection->newInstance($parameters, $targetReflection);
		}
		return false;
	}

	private function parse($reflection)
	{
		$key = $this->createName($reflection);
		if(!isset(self::$cache[$key]))
		{
			$parser = new AnnotationsMatcher;
			$parser->matches($this->getDocComment($reflection), $data);
			self::$cache[$key] = $data;
		}
		return self::$cache[$key];
	}

	private function createName($target)
	{
		if($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->name . '::' . $target->name;
		}
		elseif($target instanceof ReflectionProperty)
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