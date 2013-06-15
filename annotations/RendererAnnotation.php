<?php

/**
 * Use this annotation to set common renderer for all scenarios
 * Renderer should implement at least one of I*Renderer interface
 * @template Renderer('${RendererClass}')
 * @todo Add checks for value it it implements proper interface before assigning to each value
 * @author Piotr
 */
class RendererAnnotation extends EComponentMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$types = ['grid', 'list', 'form', 'view'];
		foreach($types as $type)
		{
			$interface = sprintf('IM%sRenderer', ucfirst($type));
			if(in_array($interface, (array)class_implements($this->value)))
			{
				$this->_entity->renderer->$type = $this->value;
			}
		}
	}
}