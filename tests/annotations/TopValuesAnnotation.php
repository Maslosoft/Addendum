<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations;

use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Helpers\ParamsExpander;

/**
 * TopValuesAnnotation
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class TopValuesAnnotation extends MetaAnnotation
{

	public $class;
	public $updatable;
	public $value;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['class', 'updatable']);
		foreach($data as $name => $value)
		{
			$this->getEntity()->$name = $value;
		}
	}

}
