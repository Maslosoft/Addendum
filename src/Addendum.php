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
use Maslosoft\Addendum\Cache\MetaCache;
use Maslosoft\Addendum\Collections\AddendumPlugins;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Interfaces\AnnotationInterface;
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
	 * Current instance id
	 * @var string
	 */
	protected $instanceId = self::DefaultInstanceId;

	/**
	 * DI
	 * @var EmbeDi
	 */
	protected $di = null;

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
	 * This holds information about all declared classes implementing AnnotatedInterface.
	 * @see AnnotatedInterface
	 * @var string[]
	 */
	private static $_annotations = [];

	/**
	 * Reflection annotated class cache
	 * @var ReflectionAnnotatedClass[]
	 */
	private static $_localCache = [];

	/**
	 * Version holder
	 * @var string
	 */
	private static $_version = null;

	/**
	 * Addendum instances
	 * @var Addendum[]
	 */
	private static $_a = [];

	/**
	 *
	 * @param string $instanceId
	 */
	public function __construct($instanceId = self::DefaultInstanceId)
	{
		$this->instanceId = $instanceId;
		$this->plugins = new AddendumPlugins($this->plugins);

		$this->di = new EmbeDi($instanceId);
		$this->di->configure($this);
	}

	/**
	 * Get flyweight addendum instance of `Addendum`.
	 * Only one instance will be created for each `$instanceId`
	 *
	 * @param string $instanceId
	 * @return Addendum
	 */
	public static function fly($instanceId = self::DefaultInstanceId)
	{
		if (empty(self::$_a[$instanceId]))
		{
			self::$_a[$instanceId] = (new Addendum($instanceId))->init();
		}
		return self::$_a[$instanceId];
	}

	/**
	 * Get current addendum version.
	 *
	 * @return string
	 */
	public function getVersion()
	{
		if (null === self::$_version)
		{
			self::$_version = require __DIR__ . '/version.php';
		}
		return self::$_version;
	}

	/**
	 * Initialize addendum and store configuration.
	 * This should be called upon first intstance creation.
	 *
	 * @return Addendum
	 */
	public function init()
	{
		if (!$this->di->isStored($this))
		{
			(new Signal())->emit(new NamespacesSignal($this));
		}
		$this->di->store($this);
		return $this;
	}

	/**
	 * Chech if class could have annotations.
	 * **NOTE:**
	 * > This does not check check if class have annotations. It only checks if it implements `AnnotatedInterface`
	 * @param string|object $class
	 * @return bool
	 */
	public function hasAnnotations($class)
	{
		return (new ReflectionClass($class))->implementsInterface(AnnotatedInterface::class);
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
			throw new ReflectionException(sprintf('To annotate class "%s", it must implement interface %s', $className, AnnotatedInterface::class));
		}
		$reflection = new ReflectionAnnotatedClass($class, $this);
		return $reflection;
	}

	/**
	 * Set logger
	 *
	 * @param LoggerInterface $logger
	 * @return Addendum
	 */
	public function setLogger(LoggerInterface $logger)
	{
		$this->_logger = $logger;
		return $this;
	}

	/**
	 * Get logger
	 *
	 * @return LoggerInterface logger
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
	 * Add annotations namespace.
	 * Every added namespace will be included in annotation name resolving for current instance.
	 *
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

			$this->di->store($this, [], true);

			// Reconfigure flyweight instances if present
			if (!empty(self::$_a[$this->instanceId]))
			{
				self::$_a[$this->instanceId]->di->configure(self::$_a[$this->instanceId]);
			}
		}
		return $this;
	}

	/**
	 * Add many annotaion namespaces.
	 * This is same as `addNamespace`, in that difference that many namespaces ant once can be added.
	 *
	 * It accepts array of namespaces as param:
	 * ```php
	 * $nss = [
	 * 		'Maslosoft\Addendum\Annotations',
	 * 		'Maslosoft\Mangan\Annotations'
	 * ];
	 * ```
	 *
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
	 * Clear entire annotations cache.
	 */
	public static function cacheClear()
	{
		self::$_annotations = [];
		self::$_classnames = [];
		self::$_localCache = [];
		self::$_rawMode = null;
		Blacklister::reset();
		Builder::clearCache();
		(new MetaCache())->clear();
	}

	/**
	 * TODO This should not be static
	 * @param \Reflector $reflection
	 * @return mixed[]
	 */
	public static function getDocComment(\Reflector $reflection)
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

	/**
	 * Raw mode test
	 * **NOTE:**
	 * > This php doc block is actually *required* for this to work
	 */
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

	/**
	 * Set raw parsing mode
	 *
	 * @param bool $enabled
	 */
	public static function setRawMode($enabled = true)
	{
		self::$_rawMode = $enabled;
	}

	/**
	 * Resolve annotation class name to prefixed annotation class name
	 *
	 * @param string $class
	 * @return string
	 */
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
				if ((new ReflectionClass($class))->implementsInterface(AnnotationInterface::class) || $class == AnnotationInterface::class)
				{
					self::$_annotations[] = $class;
				}
			}
		}
		return self::$_annotations;
	}

}
