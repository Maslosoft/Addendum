<?php
class ENestedAnnotationMatcher extends EAnnotationMatcher
{

	protected function process($result)
	{
		$builder = new EAnnotationsBuilder;
		return $builder->instantiateAnnotation($result[1], $result[2]);
	}
}