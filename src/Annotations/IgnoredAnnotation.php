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

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Use this annotation to completely ignore method or property metadata.
 * This should be used on components. This can also be used to explicitly mark that entity should be **not** ignored.
 * Examples:
 * 		&commat;Ignore() - Ignore field or method
 * 		&commat;Ignore(false) - Explicitly mark method or property as not ignored
 * @Target('property', 'method')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IgnoredAnnotation extends Annotation
{

	public $value = true;

	public function init()
	{

	}

}
