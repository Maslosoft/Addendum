<?php

namespace Maslosoft\Addendum\Matcher;

use Maslosoft\Addendum\Builder\Builder;

class NestedAnnotationMatcher extends AnnotationMatcher
{

	protected function process($result)
	{
		$builder = new Builder;
		return $builder->instantiateAnnotation($result[1], $result[2]);
	}

}
