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

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Annotation target annotation
 * This allow limiting annotation use for properties, class,
 * method or concrete type
 * Valid values are
 *
 * * `class` - limit annotation for class only
 * * `method` - limit annotation for method only
 * * `property` - limit annotation for property only
 * * `nested` - set this to allow use of annotation only as nested annotation
 * * Any existing class name - to restrict use of annotation only on concrete class or its descendants
 *
 * Examples:
 *
 * Allow only on selected class and subclasses
 *
 * ```
 * @Target(Some\Target\ClassName)
 * ```
 *
 * When use statement for `Some\Target\ClassName` is provided, it could be shortened:
 * ```
 * @Target(ClassName)
 * ```
 *
 * Several targets can be specified.
 * Only on this class and subclasses - on properties:
 * ```
 * @Target(Some\Target\ClassName)
 * @Target('property')
 * ```
 * Only on this class and subclasses - on methods:
 * ```
 * @Target('Some\Target\ClassName')
 * @Target('method')
 * ```
 * On methods and properties:
 * ```
 * @Target('method')
 * @Target('property')
 * ```
 *
 * @template Target('${target}')
 */
class TargetAnnotation extends Annotation
{

	/**
	 * For internal use
	 */
	const Ns = __NAMESPACE__;
	const TargetClass = 'class';
	const TargetMethod = 'method';
	const TargetProperty = 'property';
	const TargetNested = 'nested';

	public $value;
	public $class = '';

	public static function getTargets()
	{
		return [
			self::TargetClass,
			self::TargetMethod,
			self::TargetProperty,
			self::TargetNested
		];
	}

	public function init()
	{
		
	}

}
