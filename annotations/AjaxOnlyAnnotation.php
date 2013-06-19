<?php

/**
 * Allow only ajax requests
 * @template AjaxOnly
 */
class AjaxOnlyAnnotation extends EComponentMetaAnnotation
{

	public function init()
	{
		$this->_entity->ajaxOnly = true;
	}

}