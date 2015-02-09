<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
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
