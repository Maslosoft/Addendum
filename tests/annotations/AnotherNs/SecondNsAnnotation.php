<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations\AnotherNs;

/**
 * SecondNsAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SecondNsAnnotation extends \Maslosoft\Addendum\Collections\MetaAnnotation
{
	const Ns = __NAMESPACE__;

	public $second = false;

	public function init()
	{
		$this->getEntity()->second = true;
	}

}
