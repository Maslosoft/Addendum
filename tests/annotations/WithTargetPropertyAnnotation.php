<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * WithTargetProperty
 * @Target('property')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class WithTargetPropertyAnnotation extends MetaAnnotation
{

	public function init()
	{
		$this->getEntity()->target = 'property';
	}

}
