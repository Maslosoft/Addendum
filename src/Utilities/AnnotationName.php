<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 * AnnotationName
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AnnotationName
{
	/**
	 * Create class name
	 * @param IAnnotation $annotation
	 * @return string
	 */
	public static function createName(IAnnotation $annotation)
	{
		$parts = explode('\\', get_class($annotation));
		return preg_replace('~Annotation$~', '', array_pop($parts));
	}
}
