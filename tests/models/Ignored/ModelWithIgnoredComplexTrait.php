<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Ignored;

use Maslosoft\Addendum\Collections\Meta;

/**
 * ModelWithIgnoredTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait ModelWithIgnoredComplexTrait
{

	/**
	 * @Ignored
	 * @Label('This should be ignored anyway')
	 * @var Meta
	 */
	public $meta = null;

	/**
	 * @Ignored(false)
	 * @var type
	 */
	public $must = '';

	/**
	 * @Ignored
	 * @return Meta
	 */
	public function getMeta()
	{
		return $this->meta;
	}

	/**
	 * @Ignored(false)
	 * @return type
	 */
	public function getMust()
	{
		return $this->must;
	}

}
