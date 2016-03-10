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
 * This is wrapper for php's built in `include` to include files without warning if file does not exists.
 * This will however raise any errors if file is bogus.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class SoftIncluder
{

	/**
	 * Softly include file
	 * @param string $filename
	 * @return mixed
	 */
	public static function includeFile($filename)
	{
// Error reporting here is temporarly changed to ignore warnings.
		// This is to avvoid include warning.
		$er = error_reporting(E_ERROR);

		// Ignore file exists check for better performance, as mostly it should be there
		$data = include $filename;
		error_reporting($er);
		return $data;
	}

}
