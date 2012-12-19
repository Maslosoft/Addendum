<?php
/**
 * This is example from http://code.google.com/p/addendum/
 * Do not use it, as it will generate circular annotation error because of below examples:
 * @SingleValuedAnnotation(true)
 * @SingleValuedAnnotation(-3.141592)
 * @SingleValuedAnnotation('Hello World!')
 */
class SingleValuedAnnotation extends EAnnotation
{
	public $value;
	public function init()
	{
		// TODO Do something with value
	}
}