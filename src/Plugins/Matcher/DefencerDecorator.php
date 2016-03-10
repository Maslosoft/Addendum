<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Plugins\Matcher;

use Maslosoft\Addendum\Helpers\Fence;
use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;
use Maslosoft\Addendum\Interfaces\Plugins\Matcher\MatcherDecoratorInterface;

/**
 * Remove fenced comments from annotations.
 * This allows to write php doc with annotations - these documentation annotations will not get parsed.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DefencerDecorator implements MatcherDecoratorInterface
{

	public function decorate(MatcherInterface $matcher, &$value)
	{
		Fence::out($value);
	}

}
