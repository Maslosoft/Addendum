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

namespace Maslosoft\Addendum\Adapters;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface IMetaAdapter
{

	/**
	 * Set working component
	 * @param object $component
	 */
	public function setComponent(IAnnotated $component = null);

	/**
	 * Set meta options
	 * @param MetaOptions $options
	 */
	public function setOptions(MetaOptions $options = null);

	/**
	 * Set metadata
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta);
}
