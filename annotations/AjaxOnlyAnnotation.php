<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Allow only ajax requests
 * @template AjaxOnly
 */
class AjaxOnlyAnnotation extends MetaAnnotation
{

	public function init()
	{
		$this->_entity->ajaxOnly = true;
	}

}
