<?php
class EAnnotationTopValueMatcher extends EAnnotationValueMatcher
{

	protected function process($value)
	{
		return array('value' => $value);
	}
}