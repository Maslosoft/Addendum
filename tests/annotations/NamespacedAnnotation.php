<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * NamespacedAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NamespacedAnnotation extends MetaAnnotation
{

	const Ns = __NAMESPACE__;

	public function init()
	{
		$this->getEntity()->namespaced = true;
	}

}
