<?php

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithAnonumousClass
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithAnonumousClass implements AnnotatedInterface
{

	/**
	 * @Label('Trap')
	 * @var string
	 */
	public $trap = '';

	public function get(&$model)
	{
		/**
		 * @Label('Class Name')
		 */
		$model = new class implements AnnotatedInterface {

			/**
			 * @Label('Test')
			 * @var type
			 */
			public $test = '';

			/**
			 * @Label('Method Test')
			 */
			public function doSomething()
			{

			}
		};
	}

}
