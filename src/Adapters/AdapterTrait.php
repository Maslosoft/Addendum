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

namespace Maslosoft\Addendum\Adapters;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Options\MetaOptions;

/**
 * Common adapter functionalities
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AdapterTrait
{

	/**
	 * Component to process
	 * @var object
	 */
	protected $component = null;

	/**
	 * Meta options
	 * @var MetaOptions
	 */
	protected $options = null;

	/**
	 * Metadata
	 * @var Meta
	 */
	protected $meta = null;

	/**
	 * Set working component
	 * @param object $component
	 */
	public function setComponent(IAnnotated $component = null)
	{
		$this->component = $component;
	}

	/**
	 * Set meta options
	 * @param MetaOptions $options
	 */
	public function setOptions(MetaOptions $options = null)
	{
		$this->options = $options;
	}

	/**
	 * Set metadata
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta)
	{
		$this->meta = $meta;
	}

}
