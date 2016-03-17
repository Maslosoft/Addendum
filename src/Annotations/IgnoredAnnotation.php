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

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Use this annotation to completely ignore method or property metadata.
 * This should be used on components. This can also be used to explicitly mark that entity should be **not** ignored.
 *
 * Examples:
 *
 * Ignore field or method.
 * ```
 * @Ignored()
 * ```
 *
 * Explicitly mark method or property as not ignored. This might be usefull
 * to inform other developers that method or property must be annotated.
 * ```
 * @Ignored(false)
 * ```
 *
 *
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
