<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Ignored;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\AddendumTest\Models\Ignored\ModelWithIgnoredComplexTrait;

/**
 * ModelWithIgnoredEntities
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithIgnoredComplex extends ModelWithIgnoredBase implements AnnotatedInterface
{

	use ModelWithIgnoredComplexTrait;
}
