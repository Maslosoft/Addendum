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

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Helpers\SoftIncluder;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Cli\Shared\Helpers\PhpExporter;

/**
 * Meta Namespaces cache
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NsCache
{

	const FileName = '_ns.php';

	private $file = '';

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $ad = null;

	/**
	 *
	 * @var
	 */
	private static $nsCache = [];

	/**
	 * Hash map of namespaces.
	 * Namespaces is key, while value is boolean and is not really relevant
	 * @var array
	 */
	private $namespaces;

	/**
	 * Option holder
	 * @var MetaOptions
	 */
	private $options = null;

	/**
	 * @internal Flag that will trigger cache validity check for namespaces cache
	 * But only if no options provided
	 * @var bool
	 */
	public static $addedNs = true;

	public function __construct($path, Addendum $addendum, MetaOptions $options = null)
	{
		$this->file = sprintf('%s/%s', $path, self::FileName);
		$this->namespaces = $addendum->nameKeys;
		$this->ad = $addendum;
		$this->setOptions($options);
	}

	public function setOptions(MetaOptions $options = null)
	{
		if (!empty($options) && !empty($options->namespaces))
		{
			foreach ($options->namespaces as $ns)
			{
				if (empty($this->namespaces[$ns]))
				{
					self::$addedNs = true;
					$this->namespaces[$ns] = true;
				}
			}
		}
	}

	public function valid()
	{
		$ns = $this->get();
		$valid = $this->isValid($ns);

		// Override existing cache if not valid
		if (!$valid)
		{
			$this->set();
		}
		return $valid;
	}

	public function get()
	{
		if (!empty(self::$nsCache[$this->file]))
		{
			return self::$nsCache[$this->file];
		}
		self::$nsCache[$this->file] = SoftIncluder::includeFile($this->file);
		return self::$nsCache[$this->file];
	}

	public function set()
	{
		$ns = [];
		foreach (array_keys($this->namespaces) as $name)
		{
			$ns[$name] = true;
		}
		$data = PhpExporter::export($ns);
		$mask = umask(0);
		file_put_contents($this->file, $data);
		umask($mask);
		self::$nsCache[$this->file] = $ns;
	}

	public function remove()
	{
		unset(self::$nsCache[$this->file]);
		if (file_exists($this->file))
		{
			unlink($this->file);
		}
	}

	private function isValid($ns)
	{
		// Fresh data
		if (empty($ns))
		{
			return true;
		}

		// Additional check if added namespace manually
		if (empty($this->options) && self::$addedNs)
		{
			$addendumDiff = array_diff_key($this->ad->nameKeys, $this->namespaces);
			if (!empty($addendumDiff))
			{
				// Add missing namespaces to cache them
				foreach (array_keys($addendumDiff) as $ns)
				{
					$this->namespaces[$ns] = true;
				}
				self::$addedNs = false;
				return false;
			}
		}
		// Check if has all namespaces
		$cachedDiff = array_diff_key($this->namespaces, $ns);
		if (empty($cachedDiff))
		{
			self::$addedNs = false;
			return true;
		}
		return false;
	}

}
