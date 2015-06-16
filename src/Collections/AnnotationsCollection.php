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

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Addendum;

class AnnotationsCollection
{
	private $annotations;

	public function __construct($annotations)
	{
		$this->annotations = $annotations;
	}

	public function hasAnnotation($class)
	{
		$class = Addendum::resolveClassName($class);
		return isset($this->annotations[$class]);
	}

	public function getAnnotation($class)
	{
		$class = Addendum::resolveClassName($class);
		return isset($this->annotations[$class]) ? end($this->annotations[$class]) : false;
	}

	public function getAnnotations()
	{
		$result = [];
		foreach($this->annotations as $instances)
		{
			$result[] = end($instances);
		}
		return $result;
	}

	/**
	 * Get all annotations with optional restriction to $restriction annotation name
	 * @param string $restriction
	 * @return AnnotationInterface[]
	 */
	public function getAllAnnotations($restriction = false)
	{
		$restriction = Addendum::resolveClassName($restriction);
		$result = [];
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