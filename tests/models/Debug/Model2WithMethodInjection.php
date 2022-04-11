<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Debug;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Signals\Interfaces\SignalInterface;

/**
 * ModelWithMethodInjection
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Model2WithMethodInjection implements AnnotatedInterface
{

	/**
	 * @SlotFor(Maslosoft\SignalsTest\Signals\MethodInjected)
	 * @SlotFor(Maslosoft\SignalsTest\Signals\MethodInjected2)
	 * @SignalFor(Blah)
	 * @param SignalInterface $signal
	 */
	public function on(SignalInterface $signal)
	{
		$signal->emited = true;
	}

}
