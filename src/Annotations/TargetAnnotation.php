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

namespace Maslosoft\Addendum\Annotations;

use Maslosoft\Addendum\Annotation;

/**
 * Annotation target annotation
 * This allow limiting annotation use for properties, class,
 * method or concrete type
 * Valid values are
 * <ul>
 * <li>class - limit annotation for class only</li>
 * <li>method - limit annotation for method only</li>
 * <li>property - limit annotation for property only</li>
 * <li>nested - set this to allow use of annotatation only as nested annotation</li>
 * <li>Any existing class name - to restrict use of annotation only on concrete class or its descendants</li>
 * <ul>
 * Examples:
 *		&commat;Target(Some\Target\ClassName) - Only on this class and subclasses
 * Several targets can be specified.
 * Only on this class and subclasses - on properties:
 *		&commat;Target(Some\Target\ClassName)
 *		&commat;Target('property')
 * Only on this class and subclasses - on methods:
 *		&commat;Target('Some\Target\ClassName')
 *		&commat;Target('method')
 * On methods and properties:
 *		&commat;Target('method')
 *		&commat;Target('property')
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
