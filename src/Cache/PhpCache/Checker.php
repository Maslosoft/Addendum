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
		$fileTime = filemtime($filename);
		$cacheTime = $this->getFilename($className);
		if($fileTime > $cacheTime)
		{
			return false;
		}

		$partialsDir = $this->getPartialsDir($className);

		// Partials cache does not exists
		// assume it is not outdated
		if(!file_exists($partialsDir))
		{
			return false;
		}

		if(!is_dir($partialsDir))
		{
			return false;
		}
		NameNormalizer::normalize($usingClass, false);
		foreach (new DirectoryIterator($partialsDir) as $info)
		{
			$usingFile = $info->getFilename();
			$usingClass = basename($usingFile, '.php');
			$usingClass = str_replace('.', '\\', $usingClass);
			NameNormalizer::normalize($usingClass, false);

			// Prevent infinite loops
			if($usingClass === $className)
			{
				continue;
			}
			if (!$this->isValid($usingClass))
			{
				return false;
			}
			$usingTime = filemtime((new ReflectionClass($usingClass))->getFileName());
			if($usingTime > $fileTime)
			{
				return false;
			}

		}

		return true;
	}
}