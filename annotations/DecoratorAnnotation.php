<?php

/**
 * Use this annotation to set common decorator for all scenarios
 * Decorator should implement at least one of I*Decorator interface
 * @template Decorator('${DecoratorClass}')
 * @author Piotr
 */
class DecoratorAnnotation extends EComponentMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->decorator))
		{
			$this->_entity->decorator = new stdClass();
		}
		$types = ['grid', 'list', 'form', 'view'];
		foreach($types as $type)
		{
			if(!isset($this->_entity->decorator->$type))
			{
				$this->_entity->decorator->$type = [];
			}
			$name = array_shift($this->value);
//			var_dump($name);
//			var_dump($this->value);
//			var_dump($this->_entity->decorator->$type);
//			exit;
			
			$this->_entity->decorator->{$type}[$name] = $this->value;
		}
	}
}