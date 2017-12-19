<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;

/**
 * RequiredValidatorAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class RequiredValidatorAnnotation extends MetaAnnotation
{

	const Ns = __NAMESPACE__;

	public $value;
	public $requiredValue = '';

	public function init()
	{
		$this->getEntity()->requiredValue = $this->requiredValue;
	}

}
