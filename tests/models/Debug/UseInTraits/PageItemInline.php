<?php

namespace Maslosoft\AddendumTest\Models\Debug\UseInTraits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\CellEnumCss;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\Published;

class PageItemInline implements AnnotatedInterface
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
