<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Debug\UseInTraits;

use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\CellEnumCss;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\Published;

/**
 * PageItemAbstract
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
abstract class PageItemAbstract
{

	/**
	 * @Decorator(CellEnumCss, Published)
	 * @Label('Whenever page is published')
	 * @see CellEnumCss
	 * @see Published
	 * @var boolean
	 */
	public $published = false;

}
