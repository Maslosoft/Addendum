<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithTopValuesDefaults
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithTopValuesDefaults implements AnnotatedInterface
{

	const ClassValue = 'Maslosoft\\AddendumTest\\Models\\ModelWithLabels';
	const UpdatableValue = false;
	const TestValue = 'foo';

	/**
	 * @TopValuesDefaults('Maslosoft\AddendumTest\Models\ModelWithLabels', false)
	 * @var type 
	 */
	public $straight = null;

	/**
	 * @TopValuesDefaults(Maslosoft\AddendumTest\Models\ModelWithLabels, false)
	 * @var type
	 */
	public $withLiteral = null;

	/**
	 * @TopValuesDefaults(class = 'Maslosoft\AddendumTest\Models\ModelWithLabels', updatable = false)
	 * @var type
	 */
	public $named = null;

	/**
	 * @TopValuesDefaults(updatable = false, class = Maslosoft\AddendumTest\Models\ModelWithLabels)
	 * @var type
	 */
	public $namedReversedAndLiteral = null;

	/**
	 * @TopValuesDefaults({updatable = false, class = Maslosoft\AddendumTest\Models\ModelWithLabels})
	 * @var type
	 */
	public $asArray = null;

}
