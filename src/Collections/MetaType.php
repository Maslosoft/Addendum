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

use Maslosoft\Addendum\Interfaces\IAnnotationEntity;
use ReflectionClass;

/**
 * Container for class metadata generated by class annotations
 *
 * @author Piotr
 */
class MetaType implements IAnnotationEntity
{

	/**
	 * Class name
	 * @var string
	 */
	public $name = '';

	/**
	 * Class constructor, set some basic metadata
	 * @param ReflectionClass $info
	 */
	public function __construct(ReflectionClass $info = null)
	{
		// For internal use
		if (null === $info)
		{
			return;
		}
		$this->name = $info->name;
	}

	public function __get($name)
	{
		return null;
	}

}
