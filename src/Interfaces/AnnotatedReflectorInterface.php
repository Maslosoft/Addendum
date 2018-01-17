<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link https://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
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
