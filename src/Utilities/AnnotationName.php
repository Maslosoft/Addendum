<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 * AnnotationName
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class AnnotationName
{
	/**
	 * Create class name
	 * @param IAnnotation $annotation
	 * @return string
	 */
	public static function createName(IAnnotation $annotation)
	{
		$parts = explode('\\', get_class($annotation));
		return preg_replace('~Annotation$~', '', array_pop($parts));
	}
}
