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

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Exceptions\ConfigurationException;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;

/**
 * MatcherConfig
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MatcherConfig
{

	public function __construct($config)
	{
		assert(isset($config['addendum']), new ConfigurationException('Matcher plugins require `addendum`'));
		assert(isset($config['reflection']), new ConfigurationException('Matcher plugins require `reflection`'));
		$this->addendum = $config['addendum'];
		$this->reflection = $config['reflection'];
	}

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	public $addendum = null;

	/**
	 *
	 * @var ReflectionAnnotatedClass|ReflectionAnnotatedMethod|ReflectionAnnotatedProperty
	 */
	public $reflection = null;

}
