<?php


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