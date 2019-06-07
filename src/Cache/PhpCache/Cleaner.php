<?php


namespace Maslosoft\Addendum\Cache\PhpCache;


use function basename;
use DirectoryIterator;
use function file_exists;
use function is_dir;
use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Helpers\Cacher;
use function strpos;
use function unlink;

class Cleaner extends CacheComponent
{
	public function clean($className)
	{
		$file = $this->getFilename($className);
		if(file_exists($file))
		{
			unlink($file);
			$this->cleanBuild($file);
		}
		$partialsDir = $this->getPartialsDir($className);
		if(file_exists($partialsDir) && is_dir($partialsDir))
		{
			foreach (new DirectoryIterator($partialsDir) as $info)
			{
				$usingFile = $info->getPathname();
				if(!$info->isFile())
				{
					continue;
				}
				unlink($usingFile);
				$this->cleanBuild($usingFile);
			}
		}
	}

	private function cleanBuild($file)
	{
		// TODO Temp rough build cleaner
		$baseName = basename($file, '.php');
		$basePath = sprintf(
			'%s/%s@addendum',
			dirname($this->basePath),
			Cacher::classToFile(Builder::class)
		);
		foreach(new DirectoryIterator($basePath) as $info)
		{
			if(!$info->isFile())
			{
				continue;
			}
			$name = $info->getFilename();
			// NOTE: Coarse check, might remove more files
			if(strpos($name, $baseName) !== false)
			{
				unlink($info->getPathname());
			}
		}
	}
}