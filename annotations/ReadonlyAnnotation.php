<?php

/**
 * Readonly indicator for mongo documents
 * @template Readonly
 */
class ReadonlyAnnotation extends EModelMetaAnnotation
{
	public $value = true;

	public function init()
	{
		$this->_entity->direct = false;
		$this->_entity->readonly = (bool)$this->value;
	}

	public function __toString()
	{
		return $this->value;
	}
}