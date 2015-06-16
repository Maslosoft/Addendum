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

use Maslosoft\Addendum\Interfaces\AnnotationEntityInterface;
use ReflectionMethod;

/**
 * Container for method metadata generated by method annotations
 *
 * @author Piotr
 */
class MetaMethod implements AnnotationEntityInterface
{

	/**
	 * Name of method
	 * @var string
	 */
	public $name = '';

	/**
	 * Indicates if method is abstract
	 * @var bool
	 */
	public $isAbstract = false;

	/**
	 * Indicates if method is static
	 * @var bool
	 */
	public $isStatic = false;

	/**
	 * Class constructor, set some basic metadata
	 * @param ReflectionMethod $info
	 */
	public function __construct(ReflectionMethod $info = null)
	{
		// For internal use
		if (null === $info)
		{
			return;
		}
		$this->name = $info->name;
		$this->isAbstract = $info->isAbstract();
		$this->isStatic = $info->isStatic();
	}

	public function __get($name)
	{
		return null;
	}

}
