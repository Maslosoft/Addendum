<?php
/**
 * This is example from http://code.google.com/p/addendum/
 * Do not use it, as it will generate circular annotation error because of below example:
 * @SingleValuedAnnotationWithArray({1, 2, 3})
 */
class SingleValuedAnnotationWithArray extends EAnnotation
{
	public $value;
	public function init()
	{
		// TODO Do something with value
	}
}