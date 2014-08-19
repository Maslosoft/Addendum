<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Allow only post requests
 * @template PostOnly
 */
class PostOnlyAnnotation extends MetaAnnotation
{

	public function init()
	{
		$this->_entity->postOnly = true;
	}

}
