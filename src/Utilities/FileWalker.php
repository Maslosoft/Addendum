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
	 * Whether sum was parsed. This is to avoid duplicated files parsing.
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
		// Check if should be visited. Using realpath prevents symlink loops.
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

			// If patterns are empty, parse every file
			$parse = empty($this->patterns);

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
