<?php


namespace Maslosoft\Addendum\Cache\PhpCache;


use function basename;
use DirectoryIterator;
use function file_exists;
use function filemtime;
use function is_dir;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use ReflectionClass;
use function str_replace;

class Checker extends CacheComponent
{
	public function isValid($className)
	{
		NameNormalizer::normalize($className, false);
		$info = new ReflectionClass($className);
		$filename = $info->getFileName();
		$cacheFilename = $this->getFilename($className);
		$fileTime = filemtime($filename);

		$cacheTime = 0;
		if(file_exists($cacheFilename))
		{
			$cacheTime = filemtime($cacheFilename);
		}
		if($fileTime > $cacheTime)
		{
			return false;
		}

		$partialsDir = $this->getPartialsDir($className);

		if(file_exists($partialsDir) && is_dir($partialsDir))
		{
			NameNormalizer::normalize($className, false);
			foreach (new DirectoryIterator($partialsDir) as $info)
			{
				$usingFile = $info->getFilename();
				if (strpos($usingFile, '.php') === false)
				{
					continue;
				}
				$usingClass = basename($usingFile, '.php');
				$usingClass = str_replace('.', '\\', $usingClass);
				NameNormalizer::normalize($usingClass, false);

				if (empty($usingClass))
				{
					continue;
				}
				// Prevent infinite loops
				if ($usingClass === $className)
				{
					continue;
				}
				$usingTime = filemtime((new ReflectionClass($usingClass))->getFileName());

				// Compares partial file time with cache time
				if ($usingTime > $cacheTime)
				{
					return false;
				}

			}
		}
		return true;
	}
}