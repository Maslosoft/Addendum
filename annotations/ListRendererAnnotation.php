<?php

/**
 * Use this annotation to set list renderer
 * Renderer must implement IListRenderer
 * @template ListRenderer('${RendererClass}')
 * @author Piotr
 */
class ListRendererAnnotation extends EComponentMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$this->_entity->renderer->list = $this->value;
	}
}