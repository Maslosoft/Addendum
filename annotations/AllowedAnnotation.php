<?php

/**
 * This roles should be allowed, unless are denied by DeniedAnnotation
 * @Target('method')
 * @todo Implement AllowedAnnotation
 * @author Piotr
 */
class AllowedAnnotation extends EComponentMetaAnnotation
{
	public $value;

	public $roles = [];

	public function init()
	{
		if(is_array($this->value))
		{
			$this->_entity->roles = array_merge((array)$this->_entity->roles, $this->value);
		}
		else
		{
			$this->_entity->roles[] = $this->value;
		}
	}
}