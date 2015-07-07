<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
