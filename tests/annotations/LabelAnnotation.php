<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

/**
 * LabelAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class LabelAnnotation extends \Maslosoft\Addendum\Collections\MetaAnnotation
{

	public $value;

	public function init()
	{
		$this->getEntity()->label = (string) $this->value;
	}

}
