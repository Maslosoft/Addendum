<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * Secure
 * Enforce secure or unsecure connection for controller action with this annotation
 * @template Secure(${isSecure})
 */
class SecureAnnotation extends MetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->secure = (bool) $this->value;
	}

}
