<?php

namespace Maslosoft\Addendum;

use CApplicationComponent;
use CCache;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use ReflectionClass;
use ReflectionException;
use Yii;

Yii::setPathOfAlias('addendum', dirname(__FILE__) . '/../../');
Yii::import('addendum.annotations.*');

class Addendum extends CApplicationComponent
{
	private static $_rawMode;
	private static $_ignore;
	private static $_classnames = [];
	private static $_annotations = false;
	private static $_localCache = [];

	/**
	 * Cache component name for use with caching
	 * @var string
	 */
	public $cache = 'cache';

	public $i18nAnnotations = [
		'Label',
		'Description'
	];

	/**
	 * Cache component instance
	 * @var CCache
	 */
	private $_cache = null;

	public function hasAnnotations($class)
	{
		return (bool)isset(class_implements($class)[IAnnotated::class]);
	}

	public function meta($component)
	{
		return Meta::create($component);
	}
	
	/**
	 * Use $class name or object to annotate class
	 * Use $other in form:
	 * <ul>
	 * <li>propertyName - to annotate property</li>
	 * <li>methodName() - to annotate method - NOTE the parethises ()</li>
	 * <li>* - to annotate all properties</li>
	 * <li>*() - to annotate all methods</li>
	 * </ul>
	 * @param string|object $class
	 * @param string $other
	 * @return ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|ReflectionAnnotatedClass($class, $other = null)
	 *
	 */
	public function annotate($class, $other = null)
	{
//		var_dump(sprintf('New instance... for class %s', is_string($class)?$class:get_class($class)));
		if(!$this->hasAnnotations($class))
		{
			$className = is_object($class) ? get_class($class) : $class;
			throw new ReflectionException(sprintf('To annotate class "%s", it must implement interface %s', $className, IAnnotated::class));
		}
		if(null !== $other)
		{
			$meta = $this->annotate($class);
			if(strstr($other, '()'))
			{
				if(strstr($other, '*'))
				{
					return $meta->getMethods();
				}
				return $meta->getMethod($other);
			}
			else
			{
				if(strstr($other, '*'))
				{
					return $meta->getProperties();
				}
				return $meta->getProperty($other);
			}
		}
		$meta = $this->cacheGet($class);
		if(!$meta)
		{
			$meta = new ReflectionAnnotatedClass($class);
			$this->cacheSet($class, $meta);
		}
		return $meta;
	}

	public function init()
	{
		$this->_cache = Yii::app()->{$this->cache};
	}

	public function cacheGet($class)
	{
		$key = $this->getCacheKey($class);
		if(isset(self::$_localCache[$key]))
		{
//			echo sprintf('Local cache hit for %s<br>', $key);
			return self::$_localCache[$key];
		}
		return false;
//		echo sprintf('Trying to get cache for %s <br>', $key);
//		$value = $this->_cache->get($key);
//		self::$_localCache[$key] = $value;
//		return $value;
	}

	public function cacheSet($class, $value)
	{
		$key = $this->getCacheKey($class);
		self::$_localCache[$key] = $value;
//		$this->_cache->set($key, $value);
	}

	public function getCacheKey($class)
	{
		if(is_object($class))
		{
			$name = get_class($class);
		}
		else
		{
			$name = $class;
		}
		return sprintf('ext.adendum.%s.%s', __CLASS__, $name);
	}

	public static function getDocComment($reflection)
	{
		if(self::checkRawDocCommentParsingNeeded())
		{
			$docComment = new DocComment();
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
			$reflection = new ReflectionClass(Addendum::class);
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
				if(is_subclass_of($class, Annotation::class) || $class == Annotation::class)
				{
					self::$_annotations[] = $class;
				}
			}
		}
		return self::$_annotations;
	}
}