<?php

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 * Disallow annotation if some other annotation exists. This is to avoid using conflicting annotations.
 * This annotation can only be used on other annotation classes.
 * Only annotation name should be used here, *not* annotation class name.
 * Do not use class literals here. Only annotation name as string is recommended.
 * Example:
 * Forbid using current annotation with `CombinedAnnotation`:
 *		&commat;Conflicts('Combined')
 * @Target(\Maslosoft\Addendum\Interfaces\IAnnotation)
 * @template Conflicts('${annotation}')
 * @see IAnnotation
 */
class ConflictsAnnotation extends MetaAnnotation
{

	public $value;

	public function init()
	{
		// Init not required, $value is directly used
	}

}
