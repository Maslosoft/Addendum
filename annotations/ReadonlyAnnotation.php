<?php

/**
 * Readonly indicator for mongo documents
 */
class ReadonlyAnnotation extends EModelMetaAnnotation
{
	public $value = true;

	public function init()
	{
		$this->_meta->{$this->name}->direct = false;
		$this->_meta->{$this->name}->readonly = (bool)$this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}