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

namespace Maslosoft\Addendum\Matcher\Traits;

use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Interfaces\Matcher\MatcherInterface;

/**
 * PluginsTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait PluginsTrait
{

	/**
	 * Matcher confugration
	 * @var MatcherConfig
	 */
	private $_plugins = [];

	/**
	 * Get matcher configuration
	 * @return MatcherConfig
	 */
	public function getPlugins()
	{
		return $this->_plugins;
	}

	/**
	 * Set matcher configuration
	 * @param MatcherConfig $plugins
	 * @return MatcherInterface
	 */
	public function setPlugins(MatcherConfig $plugins)
	{
		$this->_plugins = $plugins;
		return $this;
	}

}
