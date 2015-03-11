<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Helpers;

use Maslosoft\Addendum\Interfaces\IAnnotation;

/**
 * Helper class for easier annotation params setting
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ParamsExpander
{

	/**
	 * Expand params to annotation values.
	 * When annotation is used with multi top values, this will help assign this values to annotation.
	 * Examples of multi valued params include:
	 * <ul>
	 * <li>&commat;Annotation(Class\Literal, 2)</li>
	 * <li>&commat;Annotation(class = Class\Literal, items = 2)</li>
	 * </ul>
	 * Example of use:
	 *		ParamsExpander::expand($annotation, ['class', 'items'])
	 * This will assign `$annotation->class` and `$annotation->items` with named or anonymous params (based on order of params).
	 * @param IAnnotation $annotation
	 * @param string[] $params List of parameters names in order.
	 * @param mixed[] $values Values used to expand params, if not set `$annotation->value` will be used.
	 * @return mixed[] Expanded params
	 */
	public static function expand(IAnnotation $annotation, $params, $values = null)
	{
		$values = $values? : (array)$annotation->value;
		$data = [];
		foreach ($params as $key => $name)
		{
			if (isset($values[$key]))
			{
				$data[$name] = $values[$key];
				unset($values[$key]);
				continue;
			}
			if (isset($values[$name]))
			{
				$data[$name] = $values[$name];
			}
			if (isset($annotation->$name))
			{
				$data[$name] = $annotation->$name;
			}
		}
		return $data;
	}

}
