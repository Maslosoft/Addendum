<?php

namespace Maslosoft\Addendum;

use CComponent;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use TargetAnnotation;
use UnexpectedValueException;

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
abstract class Annotation extends CComponent
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
		$class = $reflection->getName();
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
		$this->checkTargetConstraints($target);
		unset(self::$_creationStack[$class]);
	}

	public function setComponent(CComponent $component)
	{
		$this->_component = $component;
	}

	public function getUndefinedProperties()
	{
		return $this->_properties;
	}

	abstract function init();

	private function checkTargetConstraints($target)
	{
		$reflection = new ReflectionAnnotatedClass($this);
		if (!$reflection->hasAnnotation('Target'))
		{
			return;
		}
		$value = $reflection->getAnnotation('Target')->value;
		$values = is_array($value) ? $value : array($value);
		/**
		 * TODO Add concrete class constraint combined with field. ie.:
		 * Target('Some\Target\ClassName') - Only on this class and subclasses
		 * Target('Some\Target\ClassName', 'property') - Only on this class and subclasses properties
		 * Target('Some\Target\ClassName', 'method') - Only on this class and subclasses methods
		 */
		foreach ($values as $value)
		{
			if ($value == TargetAnnotation::TargetClass && $target instanceof ReflectionClass)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetMethod && $target instanceof ReflectionMethod)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetProperty && $target instanceof ReflectionProperty)
			{
				return;
			}
			if ($value == TargetAnnotation::TargetNested && $target === false)
			{
				return;
			}
		}
		if ($target === false && $value == TargetAnnotation::TargetNested)
		{
			throw new UnexpectedValueException("Annotation '" . get_class($this) . "' nesting not allowed");
		}
		elseif (in_array($value, TargetAnnotation::getTargets()))
		{
			throw new UnexpectedValueException(sprintf("Annotation '%s' not allowed on %s, it's target is %s", get_class($this), $this->createName($target), $value));
		}
	}

	private function createName($target)
	{
		if ($target instanceof ReflectionMethod)
		{
			return $target->getDeclaringClass()->getName() . '::' . $target->getName();
		}
		elseif ($target instanceof ReflectionProperty)
		{
			return $target->getDeclaringClass()->getName() . '::$' . $target->getName();
		}
		else
		{
			return $target->getName();
		}
	}

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
