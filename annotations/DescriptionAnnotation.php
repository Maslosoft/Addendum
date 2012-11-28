<?php

/**
 * Description of field, method or class, used as help tips for users
 * @template Description('${description}')
 */
class DescriptionAnnotation extends EComponentMetaAnnotation
{
	public $value;

	public function init()
	{
		$this->_entity->description = tx($this->value);
	}

	public function __toString()
	{
		return $this->value;
	}
}