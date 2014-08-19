<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Description of field, method or class, used as help tips for users
 * @template Description('${description}')
 */
class DescriptionAnnotation extends MetaAnnotation
{

	public $value;

	public function init()
	{
		$this->_entity->description = $this->value;
	}

	public function __toString()
	{
		return $this->value;
	}

}
