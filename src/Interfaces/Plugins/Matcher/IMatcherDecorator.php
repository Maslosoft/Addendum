<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Interfaces\Plugins\Matcher;

use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IMatcherDecorator extends IMatcherPlugin
{
	public function decorate(IMatcher $matcher, &$value);
}
