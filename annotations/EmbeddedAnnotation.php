<?php

/**
 * Array for embedded document in mongo
 *
 * @author Piotr
 */
class EmbeddedAnnotation extends EModelMetaAnnotation
{
	public function init()
	{
		$this->_meta->{$this->name}->embedded = $this->value;
		$this->_meta->{$this->name}->direct = false;
	}
}