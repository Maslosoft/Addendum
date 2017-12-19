<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Inherit;

/**
 * @Label('base')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface BaseInterfaceWithLabel
{

	/**
	 * @Label('get title')
	 */
	public function getTitle();
}
