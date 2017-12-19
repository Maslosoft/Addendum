<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Annotations\SignaledNs;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Signals\NamespacesSignal;

/**
 * SlotForNamespace
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SlotForNamespace implements AnnotatedInterface
{

	/**
	 *
	 * @SlotFor(NamespacesSignal)
	 * @param NamespacesSignal $signal
	 */
	public function reactOn(NamespacesSignal $signal)
	{
		$signal->addNamespace(__NAMESPACE__);
	}

}
