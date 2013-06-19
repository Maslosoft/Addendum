<?php

/**
 * Allow only post requests
 * @template PostOnly
 */
class PostOnlyAnnotation extends EComponentMetaAnnotation
{
	public function init()
	{
		$this->_entity->postOnly = true;
	}
}