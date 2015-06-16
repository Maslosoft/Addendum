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

namespace Maslosoft\Addendum;

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\AddendumPlugins;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Interfaces\IAnnotation;
use Maslosoft\Addendum\Matcher\ClassLiteralMatcher;
use Maslosoft\Addendum\Plugins\Matcher\UseResolverDecorator;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Signals\NamespacesSignal;
use Maslosoft\Addendum\Utilities\Blacklister;
use Maslosoft\Addendum\Utilities\NameNormalizer;
use Maslosoft\EmbeDi\EmbeDi;
use Maslosoft\Signals\Signal;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use ReflectionClass;
use ReflectionException;

class Addendum implements LoggerAwareInterface
{

	const DefaultInstanceId = 'addendum';

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

	/**
	 * Translatable annotations
	 * TODO This should be moved to `maslosoft/addendum-i18n-extractor`
	 * @var string[]
	 */
	public $i18nAnnotations = [
		'Label',
		'Description'
	];

	/**
	 * Plugins collection
	 * @var AddendumPlugins|mixed[]
	 */
	public $plugins = [
		'matcher' => [
			ClassLiteralMatcher::class => [
				UseResolverDecorator::class
			]
		]
	];

	/**
	 * DI
	 * @var EmbeDi
	 */
	private $di = null;

	/**
	 * Logger
	 * @var LoggerInterface
	 */
	private $_logger;

	/**
	 * If true raw doc comment parsing will be used
	 * @var boolean
	 */
	private static $_rawMode;

	/**
	 * Cache for resolved annotations class names.
	 * Key is shor annotation name.
	 * @var string[]
	 */
	private static $_classnames = [];

	/**
	 * This holds information about all declared classes implementing IAnnotated.
	 * @see IAnnotated
	 * @var string[]
	 */
	private static $_annotations = [];

	/**
	 * Reflection annotated class cache
	 * @var ReflectionAnnotatedClass[]
	 */
	private static $_localCache = [];

	public function __construct($instanceId = self::DefaultInstanceId)
	{
		$this->plugins = new AddendumPlugins($this->plugins);

		$this->di = new EmbeDi($instanceId);
		$this->di->configure($this);
	}

	public function init()
	{
		if (!$this->di->isStored($this))
		{
			(new Signal())->emit(new NamespacesSignal($this));
		}
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
		$className = is_object($class) ? get_class($class) : $class;
		if (!$this->hasAnnotations($class))
		{
			throw new ReflectionException(sprintf('To annotate class "%s", it must implement interface %s', $className, IAnnotated::class));
		}
//		$reflection = $this->cacheGet($className);
//		if (!@$reflection)
//		{
		$reflection = new ReflectionAnnotatedClass($class, $this);
//			$this->cacheSet($className, $reflection);
//		}
		return $reflection;
	}

	/**
	 * Logger
	 * @param LoggerInterface $logger
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->_logger = $logger;
	}

	/**
	 * Get logger
	 * @return LoggerInterface Get logger
	 */
	public function getLogger()
	{
		if (null === $this->_logger)
		{
			$this->_logger = new NullLogger;
		}
		return $this->_logger;
	}

	/**
	 * Add annotations namespace
	 * @param string $ns
	 * @renturn Addendum
	 */
	public function addNamespace($ns)
	{
		NameNormalizer::normalize($ns);
		if (!in_array($ns, $this->namespaces))
		{
			$this->namespaces[] = $ns;
			array_unique($this->namespaces);
			Meta::clearCache();
			$this->di->store($this, [], true);
		}
		return $this;
	}

	/**
	 * Add many annotaion namespaces
	 * @param string[] $nss
	 * @return Addendum
	 */
	public function addNamespaces($nss)
	{
		foreach ($nss as $ns)
		{
			$this->addNamespace($ns);
		}
		return $this;
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
		self::$_localCache = [];
		self::$_rawMode = null;
		Blacklister::reset();
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
