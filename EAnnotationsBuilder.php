<?php

class EAnnotationsBuilder
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
				/** @todo This suffix should be configurable*/
				$className = "{$class}Annotation";
				$annotation = $this->instantiateAnnotation($className, $params, $targetReflection);
				if($annotation !== false)
				{
					$annotations[$class][] = $annotation;
				}
			}
		}
		return new EAnnotationsCollection($annotations);
	}

	public function instantiateAnnotation($class, $parameters, $targetReflection = false)
	{
		$class = EAddendum::resolveClassName($class);
		if(is_subclass_of($class, 'EAnnotation') && !EAddendum::ignores($class) || $class == 'EAnnotation')
		{
			$annotationReflection = new ReflectionClass($class);
			return $annotationReflection->newInstance($parameters, $targetReflection);
		}
		return false;
	}

	private function parse($reflection)
	{
		$key = $this->createName($reflection);
		if(!isset(self::$cache[$key]))
		{
			$parser = new EAnnotationsMatcher;
			$parser->matches($this->getDocComment($reflection), $data);
			self::$cache[$key] = $data;
		}
		return self::$cache[$key];
	}

	private function createName($target)
	{
		if($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->getName() . '::' . $target->getName();
		}
		elseif($target instanceof ReflectionProperty)
		{
			return $target->getDeclaringClass()->getName() . '::$' . $target->getName();
		}
		else
		{
			return $target->getName();
		}
	}

	protected function getDocComment($reflection)
	{
		return EAddendum::getDocComment($reflection);
	}

	public static function clearCache()
	{
		self::$cache = array();
	}
}