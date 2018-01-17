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

namespace Maslosoft\Addendum;

use Maslosoft\Addendum\Exceptions\CircularReferenceException;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
use Maslosoft\Addendum\Utilities\ConflictChecker;
use Maslosoft\Addendum\Utilities\TargetChecker;
use ReflectionClass;
use ReflectionProperty;
use UnexpectedValueException;

/**
 * Modernized Addendum PHP Reflection Annotations
 * http://code.google.com/p/addendum/
 *
 * Copyright (C) 2006-2009 Jan "johno Suchal <johno@jsmf.net>

 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.

 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.

 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 * */
abstract class Annotation implements AnnotationInterface
{

	use Traits\MetaState;

	protected $_properties = [];
	protected $_publicProperties = [];
	private static $_creationStack = [];

	public function __construct($data = [], $target = false)
	{
		$reflection = new ReflectionClass($this);
		$class = $reflection->name;
		if (isset(self::$_creationStack[$class]))
		{
			throw new CircularReferenceException("Circular annotation reference on '$class'", E_USER_ERROR);
		}
		self::$_creationStack[$class] = true;
		foreach ($data as $key => $value)
		{
			if ($reflection->hasProperty($key))
			{
				$this->$key = $value;
			}
			$this->_properties[$key] = $value;
		}
		foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $field)
		{
			$this->_publicProperties[] = $field->name;
		}
		try
		{
			ConflictChecker::register($this);
			TargetChecker::check($this, $target);
		}
		catch (UnexpectedValueException $ex)
		{
			throw $ex;
		} finally
		{
			unset(self::$_creationStack[$class]);
		}
	}

	public function getProperties()
	{
		return $this->_properties;
	}

	/**
	 * Init annotation
	 */
	abstract public function init();

	/**
	 * Convert to array
	 * @return mixed[]
	 */
	public function toArray()
	{
		$result = [];
		foreach ($this->_publicProperties as $field)
		{
			$result[$field] = $this->$field;
		}
		return $result;
	}

}
