<?php

/**
 * Use this annotation to set grid renderer
 * Renderer must implement IGridRenderer
 * @template GridRenderer('${RendererClass}')
 * @author Piotr
 */
class GridRendererAnnotation extends EModelMetaAnnotation
{
	public $value = null;

	public function init()
	{
		if(!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$this->_entity->renderer->grid = $this->value;
	}
}