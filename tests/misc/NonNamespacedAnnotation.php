<?php

use Maslosoft\Addendum\Collections\MetaAnnotation;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * NonNamespacedAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NonNamespacedAnnotation extends MetaAnnotation
{

	public $value = false;

	public function init()
	{
		$this->getEntity()->nonNamespaced = true;
	}

}
