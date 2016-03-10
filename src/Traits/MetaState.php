<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
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
