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
		
		$this->_entity->renderer->grid = $this->value;
		$this->_entity->renderer->list = $this->value;
		$this->_entity->renderer->form = $this->value;
		$this->_entity->renderer->view = $this->value;
	}
}