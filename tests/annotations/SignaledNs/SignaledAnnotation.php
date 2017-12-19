<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations\SignaledNs;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * LabelAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SignaledAnnotation extends MetaAnnotation
{

	public $value;

	public function init()
	{
		$this->getEntity()->signaled = true;
	}

}
