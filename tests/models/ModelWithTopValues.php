<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithTopValues
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithTopValues implements AnnotatedInterface
{

	const ClassValue = 'Maslosoft\\AddendumTest\\Models\\ModelWithLabels';
	const UpdatableValue = true;
	const TestValue = 'foo';

	/**
	 * @TopValues('Maslosoft\AddendumTest\Models\ModelWithLabels', true)
	 * @var type 
	 */
	public $straight = null;

	/**
	 * @TopValues(Maslosoft\AddendumTest\Models\ModelWithLabels, true)
	 * @var type
	 */
	public $withLiteral = null;

	/**
	 * @TopValues(class = 'Maslosoft\AddendumTest\Models\ModelWithLabels', updatable = true)
	 * @var type
	 */
	public $named = null;

	/**
	 * @TopValues(updatable = true, class = Maslosoft\AddendumTest\Models\ModelWithLabels)
	 * @var type
	 */
	public $namedReversedAndLiteral = null;

	/**
	 * @TopValues({updatable = true, class = Maslosoft\AddendumTest\Models\ModelWithLabels})
	 * @var type
	 */
	public $asArray = null;

}
