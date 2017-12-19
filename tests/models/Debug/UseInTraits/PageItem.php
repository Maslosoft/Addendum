<?php

namespace Maslosoft\AddendumTest\Models\Debug\UseInTraits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

class PageItem implements AnnotatedInterface
{

	use \Maslosoft\AddendumTest\Models\Debug\UseInTraits\Traits\WithUrls;
}
