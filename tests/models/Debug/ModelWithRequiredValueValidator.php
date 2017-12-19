<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models\Debug;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * ModelWithRequiredValidator
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithRequiredValueValidator implements AnnotatedInterface
{

	const RequiredValue = 'test';

	/**
	 * @RequiredValidator(requiredValue = ModelWithRequiredValueValidator::RequiredValue)
	 * @var string
	 */
	public $login = '';

	/**
	 * @RequiredValidator(requiredValue = self::RequiredValue)
	 * @var string
	 */
	public $loginSelf = '';

	/**
	 * @RequiredValidator(requiredValue = static::RequiredValue)
	 * @var string
	 */
	public $loginStatic = '';

}
