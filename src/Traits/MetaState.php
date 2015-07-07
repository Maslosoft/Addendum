<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Traits;

/**
 * This trait can be used to implement `__set_state` magic method for meta containers.
 * Method `__set_state` is required for cache subsystem.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait MetaState
{

	public static function __set_state($data)
	{
		$object = new static;
		foreach ($data as $name => $value)
		{
			$object->$name = $value;
		}
		return $object;
	}

}
