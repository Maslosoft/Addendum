<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Collections\Meta;

/**
 * ModelWithIgnoredTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface ModelWithIgnoredComplexInterface
{

	/**
	 * Should be ignored by trait
	 * @return Meta
	 */
	public function getMeta();

	/**
	 * Should be ignored by trait
	 * @return type
	 */
	public function getMust();
}
