<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithSelfKeywordConstant
 * @Label(self::Label)
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithSelfKeywordConstant implements AnnotatedInterface
{
	const Label = 'My Label';
}
