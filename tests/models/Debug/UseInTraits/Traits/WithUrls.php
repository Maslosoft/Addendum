<?php

namespace Maslosoft\AddendumTest\Models\Debug\UseInTraits\Traits;

use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\Published;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\CellEnumCss;

trait WithUrls
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
