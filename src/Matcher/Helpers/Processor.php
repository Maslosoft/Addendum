<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher\Helpers;

use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;

/**
 * Processor
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Processor
{

	public static function process(IMatcher $matcher, $value)
	{
		return Decorator::decorate($matcher, $value);
	}

}
