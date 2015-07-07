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

	private $_metaClass = null;
	private $_component = null;

	/**
	 * Options
	 * @var string
	 */
	private $_instanceId = null;

	/**
	 * Addendum runtime path
	 * @var string
	 */
	private $_path = '';

	/**
	 *
	 * @var NsCache
	 */
	private $_nsCache = null;

	/**
	 * Runtime path
	 * @var string
	 */
	private static $_runtimePath = null;

	/**
	 * Local cacheq
	 * @var type
	 */
	private static $_cache = [];

	public function __construct($metaClass = null, AnnotatedInterface $component = null, MetaOptions $options = null)
	{
		if (null === self::$_runtimePath)
		{
			self::$_runtimePath = (new ConfigDetector)->getRuntimePath();
		}
		$this->_path = self::$_runtimePath . '/addendum';
		$this->_metaClass = $metaClass;
		$this->_component = $component;
		if (empty($options))
		{
			$this->_instanceId = Addendum::DefaultInstanceId;
		}
		else
		{
			$this->_instanceId = $options->instanceId;
		}
		$this->_nsCache = new NsCache(dirname($this->_getFilename()), Addendum::instance($this->_instanceId));
	}

	public function setComponent(AnnotatedInterface $component = null)
	{
		$this->_component = $component;
	}

	public function setOptions(MetaOptions $options = null)
	{
		$this->_nsCache->setOptions($options);
	}

	public function prepare()
	{
		if (!file_exists($this->_path))
		{
			if (!file_exists(self::$_runtimePath))
			{

				if (is_writable(dirname(self::$_runtimePath)))
				{
					mkdir(self::$_runtimePath, 0777, true);
				}
				if (!is_writable(self::$_runtimePath))
				{
					throw new RuntimeException(sprintf("Runtime path `%s` must exists and be writable", self:: $_runtimePath));
				}
			}
			if (is_writable(self::$_runtimePath))
			{
				mkdir($this->_path, 0777, true);
			}
			if (!is_writable($this->_path))
			{
				throw new RuntimeException(sprintf("Addendum runtime path `%s` must exists and be writable", $this->_path));
			}
		}
		if (!file_exists(dirname($this->_getFilename())))
		{
			mkdir(dirname($this->_getFilename()), 0777, true);
		}
	}

	public function get()
	{
		$this->prepare();
		$filename = $this->_getFilename();

		if (!$this->_nsCache->valid())
		{
			$this->_clearCurrent();
			return false;
		}

		if (isset(self::$_cache[$filename]))
		{
			return self::$_cache[$filename];
		}

		$data = SoftIncluder::includeFile($filename);

		if (empty($data))
		{
			return false;
		}
		self::$_cache[$filename] = $data;
		return $data;
	}

	public function set(Meta $meta)
	{



		$filename = $this->_getFilename();

		self::$_cache[$filename] = $meta;

		file_put_contents($filename, PhpExporter ::export($meta));
		$this->_nsCache->set();
		return $meta;
	}

	public function remove()
	{
		$filename = $this->_getFilename();
		unset(self::$_cache[$filename]);
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
		return $this->_clear($this->_path);
	}

	private function _clearCurrent()
	{
		return $this->_classToFile(dirname($this->_getFilename()));
	}

	private function _clear($path)
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

	private function _getFilename()
	{
		return sprintf('%s/%s@%s/%s.php', $this->_path, $this->_classToFile($this->_metaClass), $this->_instanceId, str_replace('\\', '/', $this->_classToFile(get_class($this->_component))));
	}

	private function _classToFile($className)
	{
		return str_replace('\\', '.', $className);
	}

}
