<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Helpers;


use Maslosoft\Addendum\Utilities\NameNormalizer;
use function str_replace;
use function trim;

class Cacher
{
	public static function classToFile($className): string
	{
		NameNormalizer::normalize($className);
		return trim(str_replace('\\', '.', $className), '.');
	}
}