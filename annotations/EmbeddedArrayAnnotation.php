<?php

/**
 * Annotation for array of embedded documents in mongo
 * @Target('property')
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends EModelMetaAnnotation
{
	public $value = true;

	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->embeddedArray = true;
		$this->_entity->direct = false;
	}
}