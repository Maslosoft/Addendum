<?php
/**
 * Annotation target annotation
 * This allow limiting annotation use for properties, class,
 * method or concrete type
 * Valid values are "class", "function", "property"
 * @todo Allow setting concrete type as target - this should limit use of annotation to selected class (interface) or subclasses
 * @template Target('${target}')
 */
class TargetAnnotation extends EAnnotation
{
	public $value;

}