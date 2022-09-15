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

namespace Maslosoft\Addendum\Cache\PhpCache;

use Maslosoft\Addendum\Helpers\SoftIncluder;

class Reader extends CacheComponent
{
	public function read($className)
	{
		$fileName = $this->getFilename($className);
		$data = SoftIncluder::includeFile($fileName);

		// Only false means not existing cache.
		// NOTE: Cache might have valid `empty`
		// value, ie. empty array.
		return $data;
	}
}