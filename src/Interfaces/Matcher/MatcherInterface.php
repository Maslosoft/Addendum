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
