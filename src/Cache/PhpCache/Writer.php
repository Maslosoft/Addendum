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


use function array_filter;
use function array_sum;
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

class Writer extends CacheComponent
{

	public function write($className, $data): bool
	{
		NameNormalizer::normalize($className, false);


		// Set partials cache
		$filter = function ($partial) use ($className) {
			$interface = AnnotatedInterface::class;
			NameNormalizer::normalize($interface, false);
			NameNormalizer::normalize($partial, false);
			if ($partial === $interface)
			{
				return false;
			}
			if ($partial === $className)
			{
				return false;
			}
			return true;
		};
		$partials = array_filter(ClassChecker::getPartials($className), $filter);
		$success = [];
		if (!empty($partials))
		{
			// Create directory for current class
			// This directory will hold class names
			// of partials
			$partialsDir = $this->getPartialsDir($className);
			if (!file_exists($partialsDir))
			{
				Io::mkdir($partialsDir);
			}

			foreach ($partials as $partialClass)
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
				$success[] = (bool)file_put_contents($classMarkerFile, PhpExporter::export(true));
			}
		}
		$fileName = $this->getFilename($className);
		$dirName = dirname($fileName);
		if(!Io::dirExists($dirName))
		{
			Io::mkdir($dirName);
		}
		$success[] = (bool)file_put_contents($fileName, PhpExporter::export($data));
		@chmod($fileName, 0666);
		return array_sum($success) === count($success);
	}
}