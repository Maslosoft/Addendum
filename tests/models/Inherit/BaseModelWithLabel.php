<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Inherit;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * BaseModelWithLabel
 * @Label('base')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class BaseModelWithLabel implements AnnotatedInterface
{

	/**
	 * @Label('Title')
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label('get title')
	 */
	public function getTitle()
	{

	}

}
