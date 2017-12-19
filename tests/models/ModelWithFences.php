<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Maslosoft\AddendumTest\Models;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 *
 * ```php
 * These annotations should be ignored:
 * @Target('property')
 * @Description('IGNORED')
 *
 * ```
 *
 * These annotations should be available:
 * @Label('LABEL')
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ModelWithFences implements AnnotatedInterface
{
	
}
