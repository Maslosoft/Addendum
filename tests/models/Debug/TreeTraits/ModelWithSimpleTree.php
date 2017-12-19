<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Debug\TreeTraits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use MongoId;

/**
 * ModelWithSimpleTree
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithSimpleTree implements AnnotatedInterface
{

	use Traits\SimpleTreeTrait;

	/**
	 * @var MongoId
	 */
	public $_id = null;

	/**
	 *
	 * @var string
	 */
	public $name = '';

	public function __construct($name = '', $children = [])
	{
		$this->name = $name;
		$this->children = $children;
	}

}
