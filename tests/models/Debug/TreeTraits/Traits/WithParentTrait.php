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

use MongoId;

/**
 * This is trait for models having parent element
 *
 * @author Piotr
 */
trait WithParentTrait
{

	/**
	 * @var MongoId
	 */
	public $parentId = null;

}
