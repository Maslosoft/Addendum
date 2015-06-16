<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Interfaces\Matcher;

use Maslosoft\Addendum\Collections\MatcherConfig;

/**
 * Matcher interface
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MatcherInterface
{

	/**
	 * Get matcher configuration
	 * @return MatcherConfig
	 */
	public function getPlugins();

	/**
	 * Set matcher configuration
	 * @param MatcherConfig $plugins
	 * @return MatcherInterface
	 */
	public function setPlugins(MatcherConfig $plugins);
}
