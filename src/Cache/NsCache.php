<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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

	private $_file = '';

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $ad = null;
	private $_ns = [];

	/**
	 *
	 * @var
	 */
	private static $_nsCache = [];

	public function __construct($path, Addendum $addendum, MetaOptions $options = null)
	{
		$this->_file = sprintf('%s/%s', $path, self::FileName);
		$this->ad = $addendum;
		$this->setOptions($options);
	}

	public function setOptions(MetaOptions $options = null)
	{
		if (!empty($options))
		{
			foreach ($options->namespaces as $ns)
			{
				if (!$this->_valid($ns))
				{
					$this->ad->addNamespace($ns);
				}
			}
		}
	}

	public function valid()
	{
		$ns = $this->get();
		$valid = $this->_valid($ns);

		// Ovverride existing cache if not valid
		if (!$valid)
		{
			$this->set();
		}
		return $valid;
	}

	public function get()
	{
		if (!empty(self::$_nsCache[$this->_file]))
		{
			return self::$_nsCache[$this->_file];
		}
		self::$_nsCache[$this->_file] = SoftIncluder::includeFile($this->_file);
		return self::$_nsCache[$this->_file];
	}

	public function set()
	{
		foreach ($this->ad->namespaces as $name)
		{
			$ns[$name] = true;
		}
		$data = PhpExporter::export($ns);
		$mask = umask(0);
		file_put_contents($this->_file, $data);
		umask($mask);
		self::$_nsCache[$this->_file] = $ns;
	}

	public function remove()
	{
		unset(self::$_nsCache[$this->_file]);
		if (file_exists($this->_file))
		{
			unlink($this->_file);
		}
	}

	private function _valid($ns)
	{
		// Fresh data
		if (empty($ns))
		{
			return true;
		}

		foreach ($this->ad->namespaces as $name)
		{
			if (empty($ns[$name]))
			{
				return false;
			}
		}
		return true;
	}

}
