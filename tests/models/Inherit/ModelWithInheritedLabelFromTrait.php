<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Inherit;

/**
 * ModelWithInheritedLabel
 * Inherited label
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithInheritedLabelFromTrait
{

	use BaseTraitWithLabel;

	/**
	 * Inherited label
	 * @var string
	 */
	public $title = '';

	/**
	 * Inherited label
	 */
	public function getTitle()
	{
		
	}

}
