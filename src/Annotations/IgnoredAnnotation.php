<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Use this annotation to completely ignore method or property metadata.
 * This should be used on components. This can also be used to explicitly mark that entity should be **not** ignored.
 * Examples:
 * 		&commat;Ignore() - Ignore field or method
 * 		&commat;Ignore(false) - Explicitly mark method or property as not ignored
 * @Target('property', 'method')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class IgnoredAnnotation extends Annotation
{

	public $value = true;

	public function init()
	{

	}

}
