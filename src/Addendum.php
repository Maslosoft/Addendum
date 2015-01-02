<?php

namespace Maslosoft\Addendum;

use CApplicationComponent;
use CCache;
use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use ReflectionClass;
use ReflectionException;
use Yii;

class Addendum extends CApplicationComponent
{
	private static $_rawMode;
	private static $_ignore;
	private static $_classnames = [];
	private static $_annotations = [];
	private static $_localCache = [];

	/**
	 * Cache component name for use with caching
	 * @var string
	 */
	public $cache = 'cache';

	/**
	 * Namespaces to check for annotations.
	 * By default global and addendum namespace is included.
	 * @var string[]
	 */
	public $namespaces = [
		'\\',
		TargetAnnotation::Ns
	];

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

	/**
	 * Use $class name or object to annotate class
	 * @param string|object $class
	 * @return ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|ReflectionAnnotatedClass
	 */
	public function annotate($class)
	{
		if(!$this->hasAnnotations($class))
		{
			$className = is_object($class) ? get_class($class) : $class;
			throw new ReflectionException(sprintf('To annotate class "%s", it must implement interface %s', $className, IAnnotated::class));
		}
		$meta = $this->cacheGet($class);
		if(!$meta)
		{
			$meta = new ReflectionAnnotatedClass($class);
			$this->cacheSet($class, $meta);
		}
		return $meta;
	}

	/**
	 * Add annotations namespace
	 * @param string $ns
	 */
	public function addNamespace($ns)
	{
		$this->namespaces[] = $ns;
		array_unique($this->namespaces);
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
		if(self::_checkRawDocCommentParsingNeeded())
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
	private static function _checkRawDocCommentParsingNeeded()
	{
		if(self::$_rawMode === null)
		{
			$reflection = new ReflectionClass(Addendum::class);
			$method = $reflection->getMethod(__FUNCTION__);
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
		self::$_ignore = [];
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
		$matching = [];
		foreach(self::_getDeclaredAnnotations() as $declared)
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

	private static function _getDeclaredAnnotations()
	{
		if(!self::$_annotations)
		{
			self::$_annotations = [];
			foreach(get_declared_classes() as $class)
			{
				if((new ReflectionClass($class))->implementsInterface(IAnnotation::class) || $class == IAnnotation::class)
				{
					self::$_annotations[] = $class;
				}
			}
		}
		return self::$_annotations;
	}
}