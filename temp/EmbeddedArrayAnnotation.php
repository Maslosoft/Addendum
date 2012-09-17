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
		$this->_meta->{$this->name}->embedded = $this->value;
		$this->_meta->{$this->name}->embeddedArray = true;
	}
}