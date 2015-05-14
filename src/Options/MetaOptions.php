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

namespace Maslosoft\Addendum\Options;

use Maslosoft\Addendum\Adapters\MetaAddendumAdapter;
use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Addendum\Collections\MetaType;

/**
 * MetaOptions
 * Options holder for Meta class
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MetaOptions
{

	/**
	 * Meta container class name for type (class)
	 * @var string
	 */
	public $typeClass = MetaType::class;

	/**
	 * Meta container class name for method
	 * @var string
	 */
	public $methodClass = MetaMethod::class;

	/**
	 * Meta container class name for property
	 * @var string
	 */
	public $propertyClass = MetaProperty::class;

	/**
	 * Extracting adapter class
	 * @var string
	 */
	public $adapterClass = MetaAddendumAdapter::class;

	/**
	 * Namespaces for search for annotations
	 * @var string[]
	 */
	public $namespaces = [];

}
