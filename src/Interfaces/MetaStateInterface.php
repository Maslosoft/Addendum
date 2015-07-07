<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Interfaces;

use Maslosoft\Addendum\Traits\MetaState;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MetaStateInterface
{

	/**
	 * This method is called when pulling object from cache.
	 * It is required for all meta (sub) containers to implement this.
	 * There is generic implementation available as trait `MetaState`.
	 * @see MetaState
	 * @param mixed $data
	 */
	public static function __set_state($data);
}
