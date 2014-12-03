<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Options;

use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Addendum\Collections\MetaType;

/**
 * MetaOptions
 * Options holder for Meta class
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MetaOptions
{

	/**
	 * Meta container class name for type (class)
	 * @var string
	 */
	public $typeClass = MetaType::class;
	/**
	 * Meta container class name for method
	 * @var string
	 */
	public $methodClass = MetaMethod::class;

	/**
	 * Meta container class name for property
	 * @var string
	 */
	public $propertyClass = MetaProperty::class;

}
