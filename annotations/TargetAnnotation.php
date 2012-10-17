<?php

/**
 * Annotation target annotation
 * This allow limiting annotation use for properties, class,
 * method or concrete type
 * Valid values are
 * <ul>
 * <li>class - limit annotation for class only</li>
 * <li>method - limit annotation for method only</li>
 * <li>property - limit annotation for property only</li>
 * <li>nested - set this to allow use of annotatation only as nested annotation</li>
 * <li>Any existing class name - to restrict use of annotation only on concrete class or its descendants</li>
 * <ul>
 * FIXME Make sure below todo works, or disable it for now
 * @todo Allow setting concrete type as target - this should limit use of annotation to selected class (interface) or subclasses
 * @template Target('${target}')
 */
class TargetAnnotation extends EAnnotation
{
	const TargetClass = 'class';
	const TargetMethod = 'method';
	const TargetProperty = 'property';
	const TargetNested = 'nested';

	public $value;
	public $class = '';

	public function init()
	{
		if(!in_array($this->value, self::getTargets()))
		{
			if(!$this->_component->_component instanceof $this->value)
			{
				throw new UnexpectedValueException(sprintf('Annotation "%s" used in "%s" is only allowed on instances of "%s"', get_class($this->_component), get_class($this->_component->_component), $this->value));
			}
		}
	}

	public static function getTargets()
	{
		return [
			 self::TargetClass,
			 self::TargetMethod,
			 self::TargetProperty,
			 self::TargetNested
		];
	}
}