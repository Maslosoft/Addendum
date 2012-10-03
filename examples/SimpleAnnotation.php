<?php

/**
 * This is sample of simple, single valued annotation
 * @Target('method')
 */
class SimpleAnnotation extends EAnnotation
{
	public $value = __CLASS__;
}