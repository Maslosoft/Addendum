<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\AddendumPlugins as Config;
use ReflectionClass;

/**
 * ModelWithUseStatements
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithUseStatements
{

	/**
	 * NOTE: These are to trick IDE to keep use statements
	 * @see ReflectionClass
	 * @see Addendum
	 * @see Annotation
	 * @see Meta
	 * @see Config
	 * @var string
	 */
	public $name = '';

}
