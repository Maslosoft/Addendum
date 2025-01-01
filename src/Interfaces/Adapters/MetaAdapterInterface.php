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

namespace Maslosoft\Addendum\Interfaces\Adapters;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface MetaAdapterInterface
{

	/**
	 * Set working component
	 * @param AnnotatedInterface|null $component
	 */
	public function setComponent(?AnnotatedInterface $component = null);

	/**
	 * Set meta options
	 * @param MetaOptions|null $options
	 */
	public function setOptions(?MetaOptions $options = null);

	/**
	 * Set metadata
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta);
}
