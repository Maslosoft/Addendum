<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Signals;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Signals\ISignal;

/**
 * NamespacesSignal
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NamespacesSignal implements ISignal
{

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $_addendum;

	public function __construct(Addendum $addendum)
	{
		$this->_addendum = $addendum;
	}

	/**
	 * Add annotation namespace
	 * @param string $namespace
	 */
	public function addNamespace($namespace)
	{
		$this->_addendum->addNamespace($namespace);
	}

}
