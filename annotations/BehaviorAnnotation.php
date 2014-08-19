<?php

/**
 * Description of BehaviorAnnotation
 * @Target('class')
 * @author Piotr
 */
class BehaviorAnnotation extends Maslosoft\Addendum\Annotation
{
	protected $class = '';
	protected $_name = '';
	private static $behaviorId = 0;

	public function init()
	{
		$name = sprintf('aBhvr-%s-%d', '%s', self::$behaviorId++);
		if(is_string($this->value))
		{
			$name = sprintf($name, $this->value);
			$this->_component->attachBehavior($name, Yii::createComponent($this->value));
		}
		else
		{
			$name = sprintf($this->_name? : $name, $this->_properties['class']);
			$config = $this->_properties;
			unset($config['_name']);
			$this->_component->attachBehavior($name, Yii::createComponent($config));
		}
	}
}