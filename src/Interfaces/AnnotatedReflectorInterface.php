<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\Addendum\Interfaces;

use Reflector;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface AnnotatedReflectorInterface extends Reflector
{

	public function hasAnnotation($class);

	public function getAnnotation($annotation);

	public function getAnnotations();

	public function getAllAnnotations($restriction = false);
}
