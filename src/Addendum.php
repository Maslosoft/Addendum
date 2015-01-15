<?php

namespace Maslosoft\Addendum;

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Storage\AddendumStorage;
use Maslosoft\EmbeDi\EmbeDi;
use ReflectionClass;
use ReflectionException;

class Addendum
{

	/**
	 * If true raw doc comment parsing will be used
	 * @var type
	 */
	private static $_rawMode;

	/**
	 * Array with ignored classes.
	 * Class name is key
	 * @var bool[]
	 */
	private static $_ignore;

	/**
	 * Cache for resolved annotations class names.
	 * Key is shor annotation name.
	 * @var string[]
	 */
	private static $_classnames = [];

	/**
	 * This holds information about all declared classes implementing IAnnotated.
	 * @see IAnnotated
	 * @var type
	 */
	private static $_annotations = [];

	/**
	 *
	 * @var type
	 */
	private static $_localCache = [];

	/**
	 * Runtime path
	 * @var string
	 */
	public $runtimePath = 'runtime';

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
	 * DI
	 * @var EmbeDi
	 */
	private $di = null;

	public function __construct()
	{
		$this->di = new EmbeDi(EmbeDi::DefaultInstanceId);
		$this->di->configure($this);
	}

	public function init()
	{
		$this->di->store($this);
	}

	/**
	 * Chech if class could have annotations
	 * @param string|object $class
	 * @return bool
	 */
	public function hasAnnotations($class)
	{
		return (new ReflectionClass($class))->implementsInterface(IAnnotated::class);
	}

	/**
	 * Use $class name or object to annotate class
	 * @param string|object $class
	 * @return ReflectionAnnotatedMethod|ReflectionAnnotatedProperty|ReflectionAnnotatedClass
	 */
	public function annotate($class)
	{
		if (!$this->hasAnnotations($class))
		{
			$className = is_object($class) ? get_class($class) : $class;
			throw new ReflectionException(sprintf('To annotate class "%s", it must implement interface %s', $className, IAnnotated::class));
		}
		$reflection = $this->cacheGet($class);
		if (!$reflection)
		{
			$reflection = new ReflectionAnnotatedClass($class);
			$this->cacheSet($class, $reflection);
		}
		return $reflection;
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

	/**
	 * Get reflection annotated class from local cache if cached or false.
	 * @param string $class
	 * @return boolean|ReflectionAnnotatedClass
	 */
	public function cacheGet($class)
	{
		$key = $this->getCacheKey($class);
		if (isset(self::$_localCache[$key]))
		{
			return self::$_localCache[$key];
		}
		return false;
	}

	/**
	 * Set reflection annotated class to local cache
	 * @param string $class
	 * @param ReflectionAnnotatedClass $value
	 */
	public function cacheSet($class, ReflectionAnnotatedClass $value)
	{
		$key = $this->getCacheKey($class);
		self::$_localCache[$key] = $value;
	}

	/**
	 * Clear local cache
	 */
	public function cacheClear()
	{
		self::$_annotations = [];
		self::$_classnames = [];
		self::$_ignore = [];
		self::$_localCache = [];
		self::$_rawMode = null;
		Builder::clearCache();
		Meta::clearCache();
	}

	public function getCacheKey($class)
	{
		if (is_object($class))
		{
			$name = get_class($class);
		}
		else
		{
			$name = $class;
		}
		return sprintf('ext.adendum.%s.%s', __CLASS__, $name);
	}

	/**
	 * TODO This should not be static
	 * @param type $reflection
	 * @return type
	 */
	public static function getDocComment($reflection)
	{
		if (self::_checkRawDocCommentParsingNeeded())
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
		if (self::$_rawMode === null)
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
		foreach (func_get_args() as $class)
		{
			self::$_ignore[$class] = true;
		}
	}

	public static function resolveClassName($class)
	{
		if (isset(self::$_classnames[$class]))
		{
			return self::$_classnames[$class];
		}
		$matching = [];
		foreach (self::_getDeclaredAnnotations() as $declared)
		{
			if ($declared == $class)
			{
				$matching[] = $declared;
			}
			else
			{
				$pos = strrpos($declared, "_$class");
				if ($pos !== false && ($pos + strlen($class) == strlen($declared) - 1))
				{
					$matching[] = $declared;
				}
			}
		}
		$result = null;
		switch (count($matching))
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
		if (!self::$_annotations)
		{
			self::$_annotations = [];
			foreach (get_declared_classes() as $class)
			{
				if ((new ReflectionClass($class))->implementsInterface(IAnnotation::class) || $class == IAnnotation::class)
				{
					self::$_annotations[] = $class;
				}
			}
		}
		return self::$_annotations;
	}

}
