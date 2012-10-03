<?php

/**
 * Array for embedded document in mongo
 * @Target('property')
 * @template Embedded
 * @author Piotr
 */
class EmbeddedAnnotation extends EModelMetaAnnotation
{
	public $value = true;
	
	public function init()
	{
		$this->_entity->embedded = $this->value;
		$this->_entity->direct = false;
	}
}