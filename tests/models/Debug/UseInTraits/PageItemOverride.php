<?php

namespace Maslosoft\AddendumTest\Models\Debug\UseInTraits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

class PageItemOverride implements AnnotatedInterface
{

	use \Maslosoft\AddendumTest\Models\Debug\UseInTraits\Traits\WithUrls;

	/**
	 * @Label('Override')
	 * @var boolean
	 */
	public $published = false;

}
