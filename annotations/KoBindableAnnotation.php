<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Indicates if attribute can be binded by knockoutjs
 * @template KoBindable(${false})
 * @author Piotr
 */
class KoBindableAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->koBindable = $this->value;
	}

}
