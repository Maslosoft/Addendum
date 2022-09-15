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

namespace Maslosoft\Addendum\Collections;

use Exception;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Cache\FlyCache;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Interfaces\MetaAnnotationInterface;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\IgnoredChecker;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Metadata container
 * @property MetaProperty $field
 * @author Piotr
 */
class Meta
{

	const Type = 1;
	const Field = 2;
	const Method = 3;

	/**
	 * Container for type metadata
	 * @var MetaType
	 */
	protected $_type = null;

	/**
	 * Array of containers for property metadata
	 * @var MetaProperty[]
	 */
	protected $_fields = [];

	/**
	 * Array of containers for method metadata
	 * @var MetaMethod[]
	 */
	protected $_methods = [];
	private $_annotations = [];

	/**
	 * On-hand cache
	 * @var Meta[]
	 */
	private static $c = [];

	/**
	 * Flag to clear local cache if namespace was dynamically added
	 * @var bool
	 */
	public static $addNs = false;

	/**
	 * @param string|object|AnnotatedInterface $model
	 * @param MetaOptions $options
	 * @throws Exception
	 */
	protected function __construct($model = null, MetaOptions $options = null)
	{
		// For internal use
		if (null === $model)
		{
			return;
		}
		if (null === $options)
		{
			$options = new MetaOptions();
		}

		// TODO Use adapter here
		// ?TODO Abstract from component meta, so other kinds of meta extractors could be used
		// For example, for development annotation based extractor could be used, which could compile
		// Metadata to arrays, and for production environment, compiled arrays could be used
		$annotations = [];
		$mes = [];

		// Get reflection data
		$ad = Addendum::fly($options->instanceId);
		$ad->addNamespaces($options->namespaces);
		$info = $ad->annotate($model);

		// Class name of working component
		$className = is_object($model) ? get_class($model) : $model;

		if (!$info instanceof ReflectionAnnotatedClass)
		{
			throw new Exception(sprintf('Could not annotate `%s`', $className));
		}

		$properties = $info->getProperties(ReflectionProperty::IS_PUBLIC);
		$defaults = $info->getDefaultProperties();

		$methods = $info->getMethods(ReflectionMethod::IS_PUBLIC);

		// Setup type
		/**
		 * @todo Fix it: $this->_meta->{$this->name}->...
		 * ^-- _meta __get and __set is only for fields AND use _fields field,
		 * for class OR methods it should use different fields
		 * for class should be _main
		 * for methods should be _methods
		 * __get and __set should distinguish it somehow... - maybe by field type EComponentMetaProperty for fields etc.
		 * Currently, disabled
		 * OR add function to Annotation to setEntity, which should point to _field, _main or _method?
		 */
		// Setup class annotations
		$this->_type = new $options->typeClass($info);
		foreach ($info->getAllAnnotations() as $annotation)
		{
			if (!$annotation instanceof MetaAnnotationInterface)
			{
				continue;
			}
			$annotation->setName($info->name);
			$annotation->setEntity($this->_type);
			$annotation->setMeta($this);
			$annotation->init();
			$annotations[] = $annotation;
		}
		// Setup methods
		foreach ($methods as $method)
		{
			if (!$method instanceof ReflectionAnnotatedMethod)
			{
				throw new Exception(sprintf('Could not annotate `%s::%s()`', $className, $method->name));
			}

			// Ignore magic methods
			if (0 === strpos($method->name, "__"))
			{
				continue;
			}

			// Ignore @Ignored marked methods
			if (IgnoredChecker::check($method))
			{
				continue;
			}

			// Create method holder class based on options
			$methodMeta = new $options->methodClass($method);
			foreach ($method->getAllAnnotations() as $annotation)
			{
				if (!$annotation instanceof MetaAnnotationInterface)
				{
					continue;
				}

				$annotation->setName($method->name);
				$annotation->setEntity($methodMeta);
				$annotation->setMeta($this);
				$annotation->init();
				$annotations[] = $annotation;
			}

			// Put it to metadata object
			$this->_methods[$method->name] = $methodMeta;

			// Get getters and setters for properties setup
			if (preg_match('~^[gs]et~', $method->name) && !$method->isStatic())
			{
				$mes[$method->name] = true;
			}
		}

		// Setup properties
		foreach ($properties as $property)
		{
			if (!$property instanceof ReflectionAnnotatedProperty)
			{
				throw new Exception(sprintf('Could not annotate `%s::%s`', $className, $property->name));
			}

			if (IgnoredChecker::check($property))
			{
				continue;
			}
			$name = $property->name;
			/* @var $property ReflectionAnnotatedProperty */
			$field = new $options->propertyClass($property);

			// Access options
			$field->callGet = isset($mes[$field->methodGet]) && $mes[$field->methodGet];
			$field->callSet = isset($mes[$field->methodSet]) && $mes[$field->methodSet];
			$field->direct = !($field->callGet || $field->callSet);
			$field->isStatic = $property->isStatic();

			// Other
			if (array_key_exists($name, $defaults))
			{
				$field->default = $defaults[$name];
			}
			// Put it to metadata object
			$this->_fields[$field->name] = $field;

			foreach ($property->getAllAnnotations() as $annotation)
			{
				if (!$annotation instanceof MetaAnnotationInterface)
				{
					continue;
				}

				$annotation->setName($field->name);
				$annotation->setEntity($field);
				$annotation->setMeta($this);
				$annotation->init();
				$annotations[] = $annotation;
			}
		}
		foreach ($annotations as $annotation)
		{
			$annotation->afterInit();
		}
	}

	/**
	 * Set state implementation
	 * @param mixed $data
	 * @return static
	 */
	public static function __set_state($data)
	{
		$obj = new static(null);
		foreach ($data as $field => $value)
		{
			$obj->$field = $value;
		}
		return $obj;
	}

	/**
	 * Create flyweight instance of `Meta`.
	 * Calling this function will create new instance only if it's not stored in cache.
	 * This allows very effective retrieving of `Meta` container's metadata, without need of parsing annotations.
	 * @param string|object|AnnotatedInterface $model
	 * @param MetaOptions|null $options
	 * @return static
	 */
	public static function create($model, MetaOptions $options = null)
	{
		// Reset local cache if dynamically added namespace
		if (self::$addNs)
		{
			self::$c = [];
			self::$addNs = false;
		}
		$class = is_object($model) ? get_class($model) : $model;
		$key = static::class . $class;
		if (isset(self::$c[$key]))
		{
			return self::$c[$key];
		}
		$cache = FlyCache::instance(static::class, $model, $options);

		$cached = $cache->get($class);
		if ($cached !== false)
		{
			self::$c[$key] = $cached;
			return $cached;
		}

		$instance = new static($model, $options);
		self::$c[$key] = $instance;
		$cache->set($class, $instance);
		return $instance;
	}

	/**
	 * Get array of properties values for property field
	 *
	 * @param string $fieldName
	 * @param int $type type of entities to return Meta::Type|Meta::Field|Meta::Method
	 * @return array
	 */
	public function properties($fieldName, $type = Meta::Field)
	{
		$result = [];
		switch ($type)
		{
			case self::Type:
				$from = $this->_type;
				break;
			case self::Field:
				$from = $this->_fields;
				break;
			case self::Method:
				$from = $this->_methods;
				break;
			default:
				$from = $this->_fields;
				break;
		}
		foreach ($from as $name => $field)
		{
			if (isset($field->$fieldName))
			{
				$result[$name] = $field->$fieldName;
			}
		}
		return $result;
	}

	/**
	 * Get fields annotations for selected field or for all fields
	 * @param string $fieldName
	 * @return mixed[]
	 * @todo Remove this
	 * @deprecated since version number
	 */
	public function annotations($fieldName = null)
	{
		if ($fieldName)
		{
			return $this->_annotations[$fieldName];
		}
		return $this->_annotations;
	}

	/**
	 * Get class metadata
	 * @return MetaType
	 */
	public function type()
	{
		return $this->_type;
	}

	/**
	 * Get all fields metadata with field name as key
	 * @return MetaProperty[]
	 */
	public function fields()
	{
		return $this->_fields;
	}

	/**
	 * Get field by name
	 * @param string $name
	 * @return MetaProperty
	 */
	public function field($name)
	{
		return $this->_fields[$name];
	}

	/**
	 * Get all methods metadata
	 * @return MetaMethod[]
	 */
	public function methods()
	{
		return $this->_methods;
	}

	/**
	 * Get method metadata by name
	 * @param string $name
	 * @return MetaMethod|bool
	 */
	public function method($name)
	{
		if (!isset($this->_methods[$name]))
		{
			return false;
		}
		return $this->_methods[$name];
	}

	/**
	 * Get fields directly-like
	 * @param string $name
	 * @return MetaProperty|boolean
	 */
	public function __get($name)
	{
		if (isset($this->_fields[$name]))
		{
			return $this->_fields[$name];
		}
		else
		{
			return false;
		}
	}

}
