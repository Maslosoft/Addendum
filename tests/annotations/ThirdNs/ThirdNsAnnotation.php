<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations\ThirdNs;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * SecondNsAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ThirdNsAnnotation extends MetaAnnotation
{

	const Ns = __NAMESPACE__;

	public $second = false;

	public function init()
	{
		$this->getEntity()->third = true;
	}

}
