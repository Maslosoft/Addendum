<?php

/**
 * Annotation for embedded document in mongo
 * defaultClassName will be used for getting empty properties,
 * but any type of embedded document can be stored within this field
 * Examples:
 * <ul>
 *		<li><b>Embedded('EmbeddedClassName')</b>: Embed with whatever set</li>
 *		<li><b>Embedded('EmbeddedClassName')</b>: Embed with default class</li>
 *		<li><b>Embedded({'EmbeddedClassName', params...})</b>: Embed with default class and optional params (currently none)</li>
 * </ul>
 * @Target('property')
 * @template Embedded('${defaultClassName}')
 * @author Piotr
 */
class EmbeddedAnnotation extends EComponentMetaAnnotation
{
	public $value = true;
	
	public function init()
	{
		$params = new stdClass();
		if(is_array($this->value))
		{
			$className = array_shift($this->value);
			$params = (object)$this->value;
		}
		else
		{
			$className = $this->value;
		}
		$this->_entity->embedded = $className;
		$this->_entity->embeddedParams = $this->value;
		$this->_entity->direct = false;
	}
}