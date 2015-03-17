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

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;

class NestedAnnotationMatcher extends AnnotationMatcher implements IMatcher
{

	protected function process($result)
	{
		$builder = new Builder;
		return $builder->instantiateAnnotation($result[1], $result[2]);
	}

}
