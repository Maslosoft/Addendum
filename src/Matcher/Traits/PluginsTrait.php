<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher\Traits;

use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Interfaces\Matcher\IMatcher;

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
	 * @return IMatcher
	 */
	public function setPlugins(MatcherConfig $plugins)
	{
		$this->_plugins = $plugins;
		return $this;
	}

}
