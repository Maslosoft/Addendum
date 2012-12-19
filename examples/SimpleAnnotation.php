<?php

/**
 * This is example from http://code.google.com/p/addendum/
 * Do not use it, as it will generate circular annotation error because of below example:
 * @SimpleAnnotation
 * @Target('method')
 */
class SimpleAnnotation extends EAnnotation
{
	public $value = __CLASS__;

	public function init()
	{
		// TODO Do something
	}
}