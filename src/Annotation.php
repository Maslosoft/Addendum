<?php

namespace Maslosoft\Addendum;

use CComponent;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Utilities\TargetChecker;
use ReflectionClass;
use ReflectionProperty;

/**
 * Addendum PHP Reflection Annotations modified for Yii
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
abstract class Annotation implements IAnnotation
{

	/**
	 * This is annotated class instance, must be set before calling init
	 * @var CComponent
	 */
	protected $_component;
	protected $_properties = [];
	protected $_publicProperties = [];
	private static $_creationStack = [];

	public function __construct($data = [], $target = false)
	{
		$reflection = new ReflectionClass($this);
		$class = $reflection->name;
		if (isset(self::$_creationStack[$class]))
		{
			trigger_error("Circular annotation reference on '$class'", E_USER_ERROR);
			return;
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
		TargetChecker::check($this, $target);
		unset(self::$_creationStack[$class]);
	}

	/**
	 * Set working component instance
	 * @param object $component
	 */
	public function setComponent($component)
	{
		$this->_component = $component;
	}

	public function getUndefinedProperties()
	{
		return $this->_properties;
	}

	/**
	 * Init annoattion
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
