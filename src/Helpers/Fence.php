<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Helpers;

/**
 * Fenceout
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Fence
{

	public static function out(&$comment)
	{
		$comment = preg_replace("~```.*?```~s", '', $comment);
	}

}
