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
use RuntimeException;

class MetaCache
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
	 * Runtime path
	 * @var string
	 */
	private static $runtimePath = null;

	/**
	 * Local cacheq
	 * @var type
	 */
	private static $cache = [];

	public function __construct($metaClass = null, AnnotatedInterface $component = null, MetaOptions $options = null)
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
		$this->nsCache = new NsCache(dirname($this->getFilename()), Addendum::fly($this->instanceId));
	}

	public function setComponent(AnnotatedInterface $component = null)
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
		$filename = $this->getFilename();

		if (!$this->nsCache->valid())
		{
			$this->clearCurrent();
			return false;
		}

		if (isset(self::$cache[$filename]))
		{
			return self::$cache[$filename];
		}

		$data = SoftIncluder::includeFile($filename);

		if (empty($data))
		{
			return false;
		}
		self::$cache[$filename] = $data;
		return $data;
	}

	public function set(Meta $meta)
	{



		$filename = $this->getFilename();

		self::$cache[$filename] = $meta;

		file_put_contents($filename, PhpExporter::export($meta));
		chmod($filename, 0666);
		$this->nsCache->set();
		return $meta;
	}

	public function remove()
	{
		$filename = $this->getFilename();
		unset(self::$cache[$filename]);
		if (file_exists($filename))
		{
			return unlink($filename);
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

	private function clearCurrent()
	{
		return $this->classToFile(dirname($this->getFilename()));
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
		return sprintf('%s/%s@%s/%s.php', $this->path, $this->classToFile($this->metaClass), $this->instanceId, str_replace('\\', '/', $this->classToFile(get_class($this->component))));
	}

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
