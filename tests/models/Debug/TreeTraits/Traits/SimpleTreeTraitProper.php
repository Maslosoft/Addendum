<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\AddendumTest\Models\Debug\TreeTraits\Traits;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * TreeTrait
 * @author Piotr
 */
trait SimpleTreeTraitProper
{

	use WithParentTrait;

	/**
	 * @RelatedArray(join = {'_id' = 'parentId'}, sort = {'order' = 1}, updateable = false)
	 * @var AnnotatedInterface[]
	 */
	public $children = [];

	/**
	 * @Label('Manual sort')
	 * @var int
	 */
	public $order = 1000000;

}
