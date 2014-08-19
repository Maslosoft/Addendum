<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Use this annotation to set list renderer
 * Renderer must implement IListRenderer
 * @template ListRenderer('${RendererClass}')
 * @author Piotr
 */
class ListRendererAnnotation extends MetaAnnotation
{

	public $value = null;

	public function init()
	{
		if (!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$this->_entity->renderer->list = $this->value;
	}

}
