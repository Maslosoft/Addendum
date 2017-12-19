<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Inherit;

/**
 * BaseTraitWithLabel
 * @Label('my label')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait BaseTraitWithLabel
{

	/**
	 * @Label('title')
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label('Get title')
	 */
	public function getTitle()
	{

	}

}
