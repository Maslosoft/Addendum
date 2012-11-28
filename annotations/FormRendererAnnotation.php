<?php

/**
 * Use this annotation to set form renderer
 * Renderer must implement IFormRenderer
 * @template FormRenderer('${RendererClass}')
 * @author Piotr
 */
class FormRendererAnnotation extends EComponentMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$this->_entity->renderer->form = $this->value;
	}
}