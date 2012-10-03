<?php
class EAnnotationValueInArrayMatcher extends EAnnotationValueMatcher
{

	public function process($value)
	{
		return array($value);
	}
}