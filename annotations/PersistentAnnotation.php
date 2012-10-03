<?php

/**
 * If true, value should be saved in database
 * @template Persistent(${false})
 * @author Piotr
 */
class PersistentAnnotation extends EModelMetaAnnotation
{
	public $value = true;

	public function init()
	{
		$this->_entity->persistent = tx($this->value);
	}
}