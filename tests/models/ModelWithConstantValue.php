<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

define('ModelWithConstantLabel', 'Some title');

/**
 * ModelWithConstantValue
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithConstantValue implements AnnotatedInterface
{

	const NameLabel = 'Some name';

	/**
	 * @Label(ModelWithConstantLabel)
	 * @var string
	 */
	public $title = '';

	/**
	 * @Label(Maslosoft\AddendumTest\Models\ModelWithConstantValue::NameLabel);
	 * @var string
	 */
	public $name = '';

}
