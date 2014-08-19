<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Use this annotation to set common renderer for all scenarios
 * Renderer should implement at least one of I*Renderer interface
 * @template Renderer('${RendererClass}')
 * @author Piotr
 */
class RendererAnnotation extends MetaAnnotation
{

	public $value = null;

	public function init()
	{
		if (!isset($this->_entity->renderer))
		{
			$this->_entity->renderer = new stdClass();
		}
		$types = ['grid', 'list', 'form', 'view'];
		foreach ($types as $type)
		{
			$this->_entity->renderer->$type = $this->value;
		}
	}

}
