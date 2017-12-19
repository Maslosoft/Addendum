<?php

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

class DecoratorAnnotation extends MetaAnnotation
{

	public $value = '';

	public function init()
	{
		$this->getEntity()->decorators = $this->value;
	}

}
