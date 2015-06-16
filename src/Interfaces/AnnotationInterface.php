<?php

/**
 * This software package is licensed under AGPL, Commercial license.
 *
 * @package maslosoft/addendum
 * @licence AGPL, Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Interfaces;

use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
interface AnnotationInterface extends IAnnotation
{

	/**
	 * Construct class
	 * @param mixed $data Raw annotations data
	 * @param ReflectionClass|ReflectionMethod|ReflectionProperty|bool $target
	 */
	public function __construct($data = [], $target = false);

	/**
	 * Set working component instance
	 * @param object $component
	 */
	public function setComponent($component);

	/**
	 * Get array of properties set by annotation, excluding default values from annotations class
	 */
	public function getProperties();

	/**
	 * Init annoattion
	 */
	public function init();

	/**
	 * Convert to array
	 * @return mixed[]
	 */
	public function toArray();
}
