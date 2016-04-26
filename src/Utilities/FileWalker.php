<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use DirectoryIterator;

/**
 * FileWalker
 *
 * This walks recursivelly on symlinks too, with loop detection.
 * Will process only files starting with Capital Letters.
 *
 * Will skip files with identical content.
 *
 * This class is meant to replace AnnotationUtility::fileWalker method.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class FileWalker
{

	/**
	 * Pahts to scan
	 * @var string[]
	 */
	private $paths = [];

	/**
	 * Patterns to match for annotations
	 * @var string[]
	 */
	private $patterns = [];

	/**
	 * Callback to execute on file
	 * @var callback
	 */
	private $callback;

	/**
	 * List of visited real paths
	 * @var bool[]
	 */
	private $visited = [];

	/**
	 * Wether sum was parsed
	 * @var bool[]
	 */
	private $sum = [];

	public function __construct($annotations, $callback, $paths)
	{
		$this->paths = $paths;
		$this->callback = $callback;
		$this->patterns = [];

		foreach ($annotations as $annotation)
		{
			$annotation = preg_replace('~^@~', '', $annotation);
			$this->patterns[] = sprintf('~@%s~', $annotation);
		}
	}

	public function walk()
	{
		foreach ($this->paths as $path)
		{
			$this->scan($path);
		}
	}

	private function scan($path)
	{
		// Check if should be visited
		$real = realpath($path);
		if (!empty($this->visited[$real]))
		{
			return;
		}
		// Mark real path as visited
		$this->visited[$real] = true;

		// Scan real path
		$iterator = new DirectoryIterator($real);
		foreach ($iterator as $info)
		{
			if ($info->isDot())
			{
				continue;
			}

			// Recurse, loop prevention check is on top of this function
			if ($info->isDir())
			{
				$this->scan($info->getPathname());
				continue;
			}

			$file = $info->getPathname();

			// Check if should be processed
			if (!empty($this->visited[$file]))
			{
				continue;
			}

			// Mark as processed
			$this->visited[$file] = true;

			// Only php
			if (!preg_match('~^.+\.php$~i', $file))
			{
				continue;
			}

			// Only starting with capital letter
			if (!preg_match('~^[A-Z]~', $info->getBasename()))
			{
				continue;
			}

			$parse = false;

			if (is_readable($file))
			{
				$contents = file_get_contents($file);
			}
			else
			{
				// TODO Log this
				continue;
			}

			// Check for file checksum
			$sum = md5($contents);
			// Check if should be processed
			if (!empty($this->sum[$sum]))
			{
				continue;
			}
			$this->sum[$sum] = true;

			foreach ($this->patterns as $pattern)
			{
				if ($parse)
				{
					continue;
				}
				if (preg_match($pattern, $contents))
				{
					$parse = true;
				}
			}
			if (!$parse)
			{
				continue;
			}
			call_user_func($this->callback, $file, $contents);
		}
	}

}
