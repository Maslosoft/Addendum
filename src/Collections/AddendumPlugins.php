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

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Gazebo\ConfigContainer;

/**
 * Plugins container
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AddendumPlugins extends ConfigContainer
{

	/**
	 * Matcher plugins
	 * @var string[]|mixed[]
	 */
	public $matcher = [];

}
