<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Cache;

use Maslosoft\Addendum\Options\MetaOptions;

/**
 * CacheOptionsOne
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class CacheOptionsTwo extends MetaOptions
{

	/**
	 * Namespaces for annotations
	 * @var string[]
	 */
	public $namespaces = [
		'Name\Space\One',
		'Name\Space\Two',
	];

}
