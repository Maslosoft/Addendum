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

namespace Maslosoft\Addendum\Cache;

use DirectoryIterator;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Helpers\SoftIncluder;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use Maslosoft\Cli\Shared\ConfigDetector;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use Maslosoft\Cli\Shared\Io;
use ReflectionClass;
use RuntimeException;
use UnexpectedValueException;
use function str_replace;
use function strpos;

/**
 * PhpCache
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class PhpCache
{

	private $metaClass = null;

	/**
	 *
	 * @var AnnotatedInterface|object|string
	 */
	private $component = null;

	/**
	 * Options
	 * @var string
	 */
	private string $instanceId ;

	/**
	 * Addendum runtime path
	 * @var string
	 */
	private string $path;

	/**
	 *
	 * @var NsCache
	 */
	private NsCache $nsCache;

	/**
	 *
	 * @var Addendum
	 */
	private Addendum $addendum;

	/**
	 * Runtime path
	 * @var ?string
	 */
	private static ?string $runtimePath = null;

	/**
	 * Local cache
	 * @var array
	 */
	private static array $cache = [];

	/**
	 * Hash map of prepared directories
	 * Key is directory, value is flag indicating if it's prepared.
	 * @var bool[]
	 */
	private static $prepared = [];
	private $fileName = null;

	/**
	 *
	 * @param string $metaClass
	 * @param AnnotatedInterface|object|string $component
	 * @param MetaOptions|Addendum $options
	 */
	public function __construct($metaClass = null, $component = null, $options = null)
	{
		if (null === self::$runtimePath)
		{
			self::$runtimePath = (new ConfigDetector)->getRuntimePath();
		}
		$this->path = self::$runtimePath . '/addendum';
		$this->metaClass = $metaClass;
		$this->component = $component;
		if ($options === null)
		{
			$this->instanceId = Addendum::DefaultInstanceId;
		}
		elseif ($options instanceof Addendum)
		{
			$this->instanceId = $options->getInstanceId();
		}
		elseif ($options instanceof MetaOptions)
		{
			$this->instanceId = $options->instanceId;
		}
		else
		{
			throw new UnexpectedValueException('Unknown options');
		}
		$this->prepare();
		$this->addendum = Addendum::fly($this->instanceId);
		$this->nsCache = new NsCache(dirname($this->getFilename()), $this->addendum);
	}

	/**
	 * Set working component
	 * @param AnnotatedInterface|object|string $component
	 */
	public function setComponent($component = null)
	{
		// Reset filename as it depends on component
		$this->fileName = null;
		$this->component = $component;
	}

	public function setOptions(?MetaOptions $options = null)
	{
		$this->fileName = null;
		$this->nsCache->setOptions($options);
	}

	/**
	 * Prepare cache storage
	 * @return bool
	 */
	private function prepare(): bool
	{
		$fileDir = dirname($this->getFilename());
		if (isset(self::$prepared[$fileDir]) && self::$prepared[$fileDir])
		{
			return true;
		}
		if (!file_exists($this->path))
		{
			if (!file_exists(self::$runtimePath))
			{

				if (is_writable(dirname(self::$runtimePath)))
				{
					Io::mkdir(self::$runtimePath);
				}
				if (!is_writable(self::$runtimePath))
				{
					throw new RuntimeException(sprintf("Runtime path `%s` must exists and be writable", self::$runtimePath));
				}
			}
			if (is_writable(self::$runtimePath))
			{
				Io::mkdir($this->path);
			}
			if (!is_writable($this->path))
			{
				throw new RuntimeException(sprintf("Addendum runtime path `%s` must exists and be writable", $this->path));
			}
		}
		if (!file_exists($fileDir))
		{
			Io::mkdir($fileDir);
		}
		self::$prepared[$fileDir] = true;
		return false;
	}

	public function get()
	{
		$this->prepare();
		$fileName = $this->getFilename();

		if (NsCache::$addedNs && !$this->nsCache->valid())
		{
			$this->clearCurrentPath();
			return false;
		}
		$key = $this->getCacheKey();
		if (isset(self::$cache[$key]))
		{
			return self::$cache[$key];
		}

		$data = SoftIncluder::includeFile($fileName);

		// Only false means not existing cache.
		// NOTE: Cache might have valid `empty` value, ie. empty array.
		if (false === $data)
		{
			return false;
		}

		// Purge file cache if checkMTime is enabled and file obsolete
		if ($this->addendum->checkMTime && file_exists($fileName))
		{
			$cacheTime = filemtime($fileName);

			// Partial component name, split by @ and take first argument
			if (is_string($this->component) && strpos($this->component, '@') !== false)
			{
				$parts = explode('@', $this->component);
				$componentClass = array_shift($parts);
			}
			else
			{
				$componentClass = $this->component;
			}
			$componentTime = filemtime((new ReflectionClass($componentClass))->getFileName());
			if ($componentTime > $cacheTime)
			{
				$this->remove();
				return false;
			}
		}
		self::$cache[$key] = $data;
		return $data;
	}

	public function set($data)
	{
		$fileName = $this->getFilename();
		$this->prepare();
		$key = $this->getCacheKey();
		self::$cache[$key] = $data;

		file_put_contents($fileName, PhpExporter::export($data));
		@chmod($fileName, 0666);
		$this->nsCache->set();
		return $data;
	}

	/**
	 * @return bool
	 */
	public function remove(): bool
	{
		$fileName = $this->getFilename();
		$key = $this->getCacheKey();
		unset(self::$cache[$key]);
		if (file_exists($fileName))
		{
			return unlink($fileName);
		}
		return false;
	}

	/**
	 * Clear entire cache
	 * @return boolean
	 */
	public function clear(): bool
	{
		self::$prepared = [];
		return $this->clearPath($this->path);
	}

	/**
	 * @return bool
	 */
	private function clearCurrentPath(): bool
	{
		$path = dirname($this->getFilename());
		return $this->clearPath($path);
	}

	/**
	 * @param string $path
	 * @return bool
	 */
	private function clearPath(string $path): bool
	{
		if (!file_exists($path))
		{
			return false;
		}
		foreach (new DirectoryIterator($path) as $dir)
		{
			if ($dir->isDot() || !$dir->isDir())
			{
				continue;
			}
			foreach (new DirectoryIterator($dir->getPathname()) as $file)
			{
				if (!$file->isFile())
				{
					continue;
				}

				// Skip ns cache file, or it might regenerate over and over
				// ns file cache is replaced when needed by NsCache
				if ($file->getBasename() === NsCache::FileName)
				{
					continue;
				}
				unlink($file->getPathname());
			}
		}
		self::$prepared[$path] = false;
		return true;
	}

	private function getFilename(): string
	{
		if (!empty($this->fileName))
		{
			return $this->cleanupFilename($this->fileName);
		}
		// NOTE: Null check is required for further get_class call
		if (null !== $this->component && is_object($this->component))
		{
			$className = get_class($this->component);
		}
		elseif (is_string($this->component) || null === $this->component)
		{
			$className = $this->component;
		}
		else
		{
			throw new UnexpectedValueException(sprintf('Expected string or object or null got: `%s`', gettype($this->component)));
		}

		if (ClassChecker::isAnonymous($className))
		{
			NameNormalizer::normalize($className);
		}

		$params = [
			(string) $this->path,
			(string) $this->classToFile($this->metaClass),
			(string) $this->instanceId,
			(string) str_replace('\\', '/', $this->classToFile($className))
		];
		$this->fileName = vsprintf('%s/%s@%s/%s.php', $params);
		return $this->cleanupFilename($this->fileName);
	}

	private function cleanupFilename(string $name): string
	{
		return str_replace("\0", "", $name);
	}

	private function getCacheKey(): string
	{
		// NOTE: Null check is required for further get_class call
		if (null !== $this->component && is_object($this->component))
		{
			$className = get_class($this->component);
		}
		else
		{
			$className = $this->component;
		}
		return sprintf('%s@%s@%s@%s.php', $this->classToFile(static::class), $this->classToFile($this->metaClass), $this->instanceId, str_replace('\\', '/', $this->classToFile($className)));
	}

	/**
	 * Convert slash separated class name to dot separated name.
	 * @param string|null $className
	 * @return string
	 */
	private function classToFile(?string $className = null): string
	{
		return str_replace('\\', '.', (string)$className);
	}
}