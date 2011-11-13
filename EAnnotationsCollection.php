<?php

class EAnnotationsCollection
{
	private $annotations;

	public function __construct($annotations)
	{
		$this->annotations = $annotations;
	}

	public function hasAnnotation($class)
	{
		$class = EAddendum::resolveClassName($class);
		return isset($this->annotations[$class]);
	}

	public function getAnnotation($class)
	{
		$class = EAddendum::resolveClassName($class);
		return isset($this->annotations[$class]) ? end($this->annotations[$class]) : false;
	}

	public function getAnnotations()
	{
		$result = array();
		foreach($this->annotations as $instances)
		{
			$result[] = end($instances);
		}
		return $result;
	}

	public function getAllAnnotations($restriction = false)
	{
		$restriction = EAddendum::resolveClassName($restriction);
		$result = array();
		foreach($this->annotations as $class => $instances)
		{
			if(!$restriction || $restriction == $class)
			{
				$result = array_merge($result, $instances);
			}
		}
		return $result;
	}
}