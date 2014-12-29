<?php

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Disallow annotation if some other annotation exists.
 * This should be used only on annotation classes
 * TODO Implement it similarly to target.
 * @template Conflicts('${annotation}')
 */
class ConflictsAnnotation extends Annotation
{

	public function init()
	{

	}

}
