<?php

/**
 * Annotation for array of embedded documents in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * @Target('property')
 * @template EmbeddedArray('${defaultClassName}')
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