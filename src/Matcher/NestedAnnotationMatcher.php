<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;

class NestedAnnotationMatcher extends AnnotationMatcher implements MatcherInterface
{

	protected function process($result)
	{
		$builder = new Builder;
		return $builder->instantiateAnnotation($result[1], $result[2]);
	}

}
