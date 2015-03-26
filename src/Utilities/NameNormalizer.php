<?php

/**
 * This software package is licensed under GNU LESSER GENERAL PUBLIC LICENSE license.
 *
 * @package maslosoft/signals
 * @licence GNU LESSER GENERAL PUBLIC LICENSE
 *
 * @copyright Copyright (c) Peter Maselkowski <pmaselkowski@gmail.com>
 *
 */

namespace Maslosoft\Addendum\Utilities;

/**
 * This class normalize class names into consistent values.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NameNormalizer
{

	public static function normalize(&$className, $trailingSlash = true)
	{
		$replaces = [
			'~\\\+~' => '\\',
			'~^\\\~' => '',
			'~\\\$~' => '',
		];

		$className = preg_replace(array_keys($replaces), $replaces, $className);
		if ($trailingSlash)
		{
			$className = '\\' . $className;
		}
	}

}
