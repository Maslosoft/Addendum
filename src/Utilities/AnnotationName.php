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

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Interfaces\AnnotationInterface;

/**
 * AnnotationName
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AnnotationName
{
	/**
	 * Create class name
	 * @param AnnotationInterface $annotation
	 * @return string
	 */
	public static function createName(AnnotationInterface $annotation)
	{
		$parts = explode('\\', get_class($annotation));
		return preg_replace('~Annotation$~', '', array_pop($parts));
	}
}
