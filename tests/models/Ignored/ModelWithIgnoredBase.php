<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Ignored;

/**
 * ModelWithIgnoredEntities
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithIgnoredBase
{

	/**
	 * @Label('Title')
	 * @var type
	 */
	public $title = 'My title';

	public function getTitle()
	{
		return $this->title;
	}

}
