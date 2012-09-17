<?php

/**
 * Description for mongo documents
 */
class DescriptionAnnotation extends EModelMetaAnnotation
{
	public $value;

	public function init()
	{
		$this->_meta->{$this->name}->description = tx($this->value);
	}

	public function __toString()
	{
		return $this->value;
	}
}