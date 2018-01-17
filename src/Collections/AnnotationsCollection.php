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

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;

class AnnotationsCollection
{

	/**
	 * Annotations holder
	 * @var AnnotationInterface[]
	 */
	private $annotations;

	public function __construct($annotations)
	{
		$this->annotations = $annotations;
	}

	/**
	 * Check if has annotation.
	 * This method should be used with annotation name, not class name, ie:
	 *
	 * ```
	 * $this->hasAnnotation('Label');
	 * ```
	 *
	 * @param string $name
	 * @return bool
	 */
	public function hasAnnotation($name)
	{
		$class = Addendum::resolveClassName($name);
		return isset($this->annotations[$class]);
	}

	/**
	 * Get annotation.
	 * This method should be used with annotation name, not class name, ie:
	 *
	 * ```
	 * $this->getAnnotation('Label');
	 * ```
	 *
	 * @param string $name
	 * @return AnnotationInterface
	 */
	public function getAnnotation($name)
	{
		$class = Addendum::resolveClassName($name);
		return isset($this->annotations[$class]) ? end($this->annotations[$class]) : false;
	}

	public function getAnnotations()
	{
		$result = [];
		foreach ($this->annotations as $instances)
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
		foreach ($this->annotations as $class => $instances)
		{
			if (!$restriction || $restriction == $class)
			{
				$result = array_merge($result, $instances);
			}
		}
		return $result;
	}

}
