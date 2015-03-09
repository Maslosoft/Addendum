<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Signals;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Signals\ISignal;

/**
 * NamespacesSignal
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class NamespacesSignal implements ISignal
{

	/**
	 * Addendum instance
	 * @var Addendum
	 */
	private $_addendum;

	public function __construct(Addendum $addendum)
	{
		$this->_addendum = $addendum;
	}

	/**
	 * Add annotation namespace
	 * @param string $namespace
	 */
	public function addNamespace($namespace)
	{
		$this->_addendum->addNamespace($namespace);
	}

}
