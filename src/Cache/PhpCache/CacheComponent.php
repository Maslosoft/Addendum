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


use Maslosoft\Addendum\Helpers\Cacher;
use function sprintf;
use function str_replace;

class CacheComponent
{
	protected string $basePath = '';

	public function __construct($basePath)
	{
		$this->basePath = $basePath;
	}

	protected function getFilename($className): string
	{
		return $this->cleanupFilename(sprintf("%s/%s.php", $this->basePath, Cacher::classToFile($className)));
	}

	protected function getPartialsDir($className): string
	{
		return sprintf(
			'%s/%s',
			$this->basePath,
			Cacher::classToFile($className)
		);
	}

	private function cleanupFilename(string $name): string
	{
		return str_replace("\0", "", $name);
	}
}