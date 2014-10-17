<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * If true, value should be saved in database.
 * By default all public properties are stored into db, so use it only when
 * property should not be stored
 * @Target('property')
 * @template Persistent(${false})
 * @author Piotr
 */
class PersistentAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->persistent = (bool)$this->value;
	}

}
