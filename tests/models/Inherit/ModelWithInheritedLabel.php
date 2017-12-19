<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Inherit;

/**
 * ModelWithInheritedLabel
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithInheritedLabel extends BaseModelWithLabel
{

	/**
	 * Inherited property
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
