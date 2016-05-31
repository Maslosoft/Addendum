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
		if ($className === '\\')
		{
			return;
		}
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
		return $className;
	}

}
