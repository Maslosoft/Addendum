<?php

/**
 * Secure
 * Enforce secure or unsecure connection for controller action with this annotation
 * @template Secure(${isSecure})
 */
class SecureAnnotation extends EComponentMetaAnnotation
{

	public $value = true;

	public function init()
	{
		$this->_entity->secure = (bool) $this->value;
	}

}