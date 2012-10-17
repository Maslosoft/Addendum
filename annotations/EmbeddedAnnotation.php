<?php

/**
 * Annotation for embedded document in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * @Target('property')
 * @template Embedded('${defaultClassName}')
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