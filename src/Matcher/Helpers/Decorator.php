<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher\Helpers;

use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;

/**
 * Decorator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Decorator
{

	public static function decorate(IMatcher $matcher, $value)
	{
		return $value;
	}

}
