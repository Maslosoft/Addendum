<?php

/**
 * If true, value should be saved in database
 *
 * @author Piotr
 */
class PersistentAnnotation extends EModelMetaAnnotation
{
	public $value = true;

	public function init()
	{
		$this->_meta->{$this->name}->persistent = tx($this->value);
	}
}