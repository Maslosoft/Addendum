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
