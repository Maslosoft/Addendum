<?php


namespace Maslosoft\Addendum\Cache\PhpCache;


use Maslosoft\Addendum\Helpers\Cacher;
use function sprintf;

class CacheComponent
{
	protected $basePath = '';

	public function __construct($basePath)
	{
		$this->basePath = $basePath;
	}

	protected function getFilename($className)
	{
		return sprintf("%s/%s.php", $this->basePath, Cacher::classToFile($className));
	}

	protected function getPartialsDir($className)
	{
		return sprintf(
			'%s/%s',
			$this->basePath,
			Cacher::classToFile($className)
		);
	}
}