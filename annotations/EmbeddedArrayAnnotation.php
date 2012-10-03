<?php

/**
 * Annotation for array of embedded documents in mongo
 *
 * @author Piotr
 */
class EmbeddedArrayAnnotation extends EModelMetaAnnotation
{
	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->embeddedArray = true;
	}
}