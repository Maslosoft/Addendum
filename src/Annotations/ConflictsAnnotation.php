<?php

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 * Disallow annotation if some other annotation exists.
 * This should be used only on annotation classes.
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
