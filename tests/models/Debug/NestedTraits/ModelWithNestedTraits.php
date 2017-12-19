<?php

namespace Maslosoft\AddendumTest\Models\Debug\NestedTraits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithSimpleTree
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithNestedTraits implements AnnotatedInterface
{

	use TestSimpleTreeTrait;

	/**
	 * @Label('My Label')
	 * @var string
	 */
	public $hasLabel = '';

}
