<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Matcher\Traits;

/**
 * PluginsTrait
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait PluginsTrait
{

	/**
	 * Plugins
	 * @var mixed[]
	 */
	private $_plugins = [];

	public function getPlugins()
	{
		return $this->_plugins;
	}

	public function setPlugins($plugins)
	{
		$this->_plugins = $plugins;
		return $this;
	}

}
