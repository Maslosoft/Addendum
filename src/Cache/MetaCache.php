<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Cache;

use FilesystemIterator;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Helpers\SoftIncluder;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Cli\Shared\ConfigDetector;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RuntimeException;

class MetaCache extends PhpCache
{

	private $metaClass = null;
	private $component = null;

	/**
	 * Options
	 * @var string
	 */
	private $instanceId = null;

	/**
	 * Addendum runtime path
	 * @var string
	 */
	private $path = '';

	/**
	 *
	 * @var NsCache
	 */
	private $nsCache = null;

	/**
	 *
	 * @var Addendum
	 */
	private $addendum = null;

	/**
	 * Runtime path
	 * @var string
	 */
	private static $runtimePath = null;

	/**
	 * Local cacheq
	 * @var type
	 */
	private static $cache = [];

	/**
	 *
	 * @param string $metaClass
	 * @param AnnotatedInterface|string $component
	 * @param MetaOptions $options
	 */
	public function __construct($metaClass = null, $component = null, MetaOptions $options = null)
	{
		if (null === self::$runtimePath)
		{
			self::$runtimePath = (new ConfigDetector)->getRuntimePath();
		}
		$this->path = self::$runtimePath . '/addendum';
		$this->metaClass = $metaClass;
		$this->component = $component;
		if (empty($options))
		{
			$this->instanceId = Addendum::DefaultInstanceId;
		}
		else
		{
			$this->instanceId = $options->instanceId;
		}
		$this->addendum = Addendum::fly($this->instanceId);
		$this->nsCache = new NsCache(dirname($this->getFilename()), $this->addendum);
	}

	/**
	 * Set working component
	 * @param AnnotatedInterface|string $component
	 */
	public function setComponent($component = null)
	{
		$this->component = $component;
	}

	public function setOptions(MetaOptions $options = null)
	{
		$this->nsCache->setOptions($options);
	}

	public function prepare()
	{
		if (!file_exists($this->path))
		{
			if (!file_exists(self::$runtimePath))
			{

				if (is_writable(dirname(self::$runtimePath)))
				{
					$this->mkdir(self::$runtimePath);
				}
				if (!is_writable(self::$runtimePath))
				{
					throw new RuntimeException(sprintf("Runtime path `%s` must exists and be writable", self::$runtimePath));
				}
			}
			if (is_writable(self::$runtimePath))
			{
				$this->mkdir($this->path);
			}
			if (!is_writable($this->path))
			{
				throw new RuntimeException(sprintf("Addendum runtime path `%s` must exists and be writable", $this->path));
			}
		}
		if (!file_exists(dirname($this->getFilename())))
		{
			$this->mkdir(dirname($this->getFilename()));
		}
	}

	public function get()
	{
		$this->prepare();
		$fileName = $this->getFilename();

		if (!$this->nsCache->valid())
		{
			$this->clearCurrentPath();
			return false;
		}
		$key = $this->getCacheKey($fileName);
		if (isset(self::$cache[$key]))
		{
			return self::$cache[$key];
		}

		$data = SoftIncluder::includeFile($fileName);

		if (empty($data))
		{
			return false;
		}

		// Purge file cache if checkMTime is enabled and file obsolete
		if ($this->addendum->checkMTime)
		{
			$cacheTime = filemtime($fileName);
			$componentTime = filemtime((new ReflectionClass($this->component))->getFileName());
			if ($componentTime > $cacheTime)
			{
				$this->clearCurrentFile();
				return false;
			}
		}
		self::$cache[$key] = $data;
		return $data;
	}

	public function set(Meta $meta)
	{
		$fileName = $this->getFilename();
		$this->prepare();
		$key = $this->getCacheKey($fileName);
		self::$cache[$key] = $meta;

		file_put_contents($fileName, PhpExporter::export($meta));
		chmod($fileName, 0666);
		$this->nsCache->set();
		return $meta;
	}

	public function remove()
	{
		$fileName = $this->getFilename();
		$key = $this->getCacheKey($fileName);
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
	public function clear()
	{
		return $this->clearPath($this->path);
	}

	private function clearCurrentPath()
	{
		return $this->clearPath(dirname($this->getFilename()));
	}

	private function clearCurrentFile()
	{
		return $this->clearPath($this->getFilename());
	}

	private function clearPath($path)
	{
		if (!file_exists($path))
		{
			return false;
		}
		foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $dir)
		{
			$dir->isDir() && !$dir->isLink() ? rmdir($dir->getPathname()) : unlink($dir->getPathname());
		}
		return rmdir($path);
	}

	private function getFilename()
	{
		if (is_object($this->component))
		{
			$className = get_class($this->component);
		}
		else
		{
			$className = $this->component;
		}
		return sprintf('%s/%s@%s/%s.php', $this->path, $this->classToFile($this->metaClass), $this->instanceId, str_replace('\\', '/', $this->classToFile($className)));
	}

	private function getCacheKey($fileName)
	{
		return sprintf('%s@%s', static::class, $fileName);
	}

	/**
	 * Convert slash separated class name to dot separated name.
	 * @param string $className
	 * @return string
	 */
	private function classToFile($className)
	{
		return str_replace('\\', '.', $className);
	}

	/**
	 * Recursively create dir with proper permissions.
	 * 
	 * @param string $path
	 */
	private function mkdir($path)
	{
		$mask = umask(0000);
		mkdir($path, 0777, true);
		umask($mask);
	}

}
