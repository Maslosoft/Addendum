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

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;

/**
 * Disallow annotation if some other annotation exists. This is to avoid using conflicting annotations.
 * This annotation can only be used on other annotation classes.
 * Only annotation name should be used here, *not* annotation class name.
 * Do not use class literals here. Only annotation name as string is recommended.
 * Example:
 * Forbid using current annotation with `CombinedAnnotation`:
 *		&commat;Conflicts('Combined')
 * @Target(\Maslosoft\Addendum\Interfaces\AnnotationInterface)
 * @template Conflicts('${annotation}')
 * @see AnnotationInterface
 */
class ConflictsAnnotation extends MetaAnnotation
{

	public $value;

	public function init()
	{
		// Init not required, $value is directly used
	}

}
