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

namespace Maslosoft\Addendum\Helpers;

/**
 * Remove fence-like doc comments from comment block.
 *
 * This is used to remove comments blocks from parsing,
 * so php docs can contain annotations example usage.
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
