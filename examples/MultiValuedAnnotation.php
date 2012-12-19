<?php

/**
 * This is example from http://code.google.com/p/addendum/
 * Do not use it, as it will generate circular annotation error because of below example:
 * @MultiValuedAnnotation(key = 'value', anotherKey = false, andMore = 1234)
 */
class MultiValuedAnnotation extends EAnnotation
{
	public $key;
	public $anotherKey;
	public $andMore;

	public function init()
	{
		// TODO Do something with values
	}
}