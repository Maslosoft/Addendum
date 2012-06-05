<?php

Yii::import('application.extensions.addendum.*');

class EAddendum extends CApplicationComponent
{
	private static $_rawMode;
	private static $_ignore;
	private static $_classnames = array();
	private static $_annotations = false;

	/**
	 * Use $class name or object to annotate class
	 * Use $other in form:
	 * propertyName - to annotate property
	 * methodName() - to annotate method - NOTE the parethises ()
	 * * - to annotate all properties
	 * *() - to annotate all methods
	 * @param string|object $class
	 * @param string $other
	 * @return \ReflectionAnnotatedMethod|\ReflectionAnnotatedProperty|\ReflectionAnnotatedClass
	 */
	public function annotate($class, $other = null)
	{
		if(null !== $other)
		{
			if(strstr($other, '()'))
			{
				if(strstr($other, '*'))
				{
					$meta = $this->annotate($class);
					return $meta->getMethods();
				}
				return new ReflectionAnnotatedMethod($class, $other);
			}
			else
			{
				if(strstr($other, '*'))
				{
					$meta = $this->annotate($class);
					return $meta->getProperties();
				}
				return new ReflectionAnnotatedProperty($class, $other);
			}
		}
		return new ReflectionAnnotatedClass($class);
	}

	public function init()
	{
		
	}

	public static function getDocComment($reflection)
	{
		if(self::checkRawDocCommentParsingNeeded())
		{
			$docComment = new EDocComment();
			return $docComment->get($reflection);
		}
		else
		{
			return $reflection->getDocComment();
		}
	}

	/** Raw mode test */
	private static function checkRawDocCommentParsingNeeded()
	{
		if(self::$_rawMode === null)
		{
			$reflection = new ReflectionClass('EAddendum');
			$method = $reflection->getMethod('checkRawDocCommentParsingNeeded');
			self::setRawMode($method->getDocComment() === false);
		}
		return self::$_rawMode;
	}

	public static function setRawMode($enabled = true)
	{
		self::$_rawMode = $enabled;
	}

	public static function resetIgnoredAnnotations()
	{
		self::$_ignore = array();
	}

	public static function ignores($class)
	{
		return isset(self::$_ignore[$class]);
	}

	public static function ignore()
	{
		foreach(func_get_args() as $class)
		{
			self::$_ignore[$class] = true;
		}
	}

	public static function resolveClassName($class)
	{
		if(isset(self::$_classnames[$class]))
			return self::$_classnames[$class];
		$matching = array();
		foreach(self::getDeclaredAnnotations() as $declared)
		{
			if($declared == $class)
			{
				$matching[] = $declared;
			}
			else
			{
				$pos = strrpos($declared, "_$class");
				if($pos !== false && ($pos + strlen($class) == strlen($declared) - 1))
				{
					$matching[] = $declared;
				}
			}
		}
		$result = null;
		switch(count($matching))
		{
			case 0: $result = $class;
				break;
			case 1: $result = $matching[0];
				break;
			default: trigger_error("Cannot resolve class name for '$class'. Possible matches: " . join(', ', $matching), E_USER_ERROR);
		}
		self::$_classnames[$class] = $result;
		return $result;
	}

	private static function getDeclaredAnnotations()
	{
		if(!self::$_annotations)
		{
			self::$_annotations = array();
			foreach(get_declared_classes() as $class)
			{
				if(is_subclass_of($class, 'EAnnotation') || $class == 'EAnnotation')
					self::$_annotations[] = $class;
			}
		}
		return self::$_annotations;
	}
}