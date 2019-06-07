<?php


namespace Maslosoft\Addendum\Cache\PhpCache;


use function array_filter;
use function chmod;
use function dirname;
use function file_exists;
use function file_put_contents;
use Maslosoft\Addendum\Helpers\Cacher;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Cli\Shared\Io;
use function sprintf;

class Writer
{
	private $basePath = '';

	public function __construct($basePath)
	{
		$this->basePath = $basePath;
	}

	public function write($className, $data)
	{
		NameNormalizer::normalize($className);


		// Set partials cache
		$filter = function ($partial) use($className) {
			if($partial === AnnotatedInterface::class)
			{
				return false;
			}
			if($partial === $className)
			{
				return false;
			}
			return true;
		};
		$partials = array_filter(ClassChecker::getPartials($className), $filter);

		if (!empty($partials))
		{
			// Create directory for current class
			// This directory will hold class names
			// of partials
			$partialsDir = sprintf(
				'%s/%s',
				$this->basePath,
				Cacher::classToFile($className)
			);
			if (!file_exists($partialsDir))
			{
				Io::mkdir($partialsDir);
			}

			foreach (ClassChecker::getPartials($className) as $partialClass)
			{
				$partialFile = Cacher::classToFile($partialClass);

				// Write current class name to partial dir
				// so that it can be retrieved on modification
				// time check
				$classMarkerFile = sprintf(
					'%s/%s.php',
					$partialsDir,
					$partialFile
				);
				file_put_contents($classMarkerFile, PhpExporter::export(true));
			}
		}
		$fileName = sprintf("%s/%s.php", $this->basePath, Cacher::classToFile($className));
		file_put_contents($fileName, PhpExporter::export($data));
		@chmod($fileName, 0666);
	}
}