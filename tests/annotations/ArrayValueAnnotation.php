<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.12.17
 * Time: 11:00
 */

namespace Maslosoft\AddendumTest\Annotations;


use Maslosoft\Addendum\Collections\MetaAnnotation;

class ArrayValueAnnotation extends MetaAnnotation
{
	public $value;

	public function init()
	{
		$this->getEntity()->values = (array)$this->value;
	}
}