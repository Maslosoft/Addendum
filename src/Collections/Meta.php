<?php

/**
 * This software package is licensed under New BSD license.
 *
 * @package maslosoft/addendum
 * @licence New BSD
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com> (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Maslosoft (Meta container, further improvements, bugfixes)
 * @copyright Copyright (c) Jan Suchal (Original version, builder, parser)
 * @link http://maslosoft.com/addendum/ - maslosoft addendum
 * @link https://code.google.com/p/addendum/ - original addendum project
 */

namespace Maslosoft\Addendum\Collections;

use Exception;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Interfaces\IAnnotated;
use Maslosoft\Addendum\Interfaces\IMetaAnnotation;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\IgnoredChecker;
use Maslosoft\Signals\Helpers\NameNormalizer;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Description of EComponentMeta
 * @property MetaProperty $field
 * @author Piotr
 */
class Meta
{

	const Type = 1;
	const Field = 2;
	const Method = 3;

	/**
	 * Metadata containers
	 * @var Meta[]
	 */
	private static $_instances = [];

	/**
	 * Namespaces cache
	 * @var string[][]
	 */
	private static $_ns = [];

	/**
	 * Container for type metadata
	 * @var MetaType
	 */
	private $_type = null;

	/**
	 * Array of containers for property metadata
	 * @var MetaProperty[]
	 */
	private $_fields = [];

	/**
	 * Array of containers for method metadata
	 * @var MetaMethod[]
	 */
	private $_methods = [];
	private $_annotations = [];

	protected function __construct(IAnnotated $component = null, MetaOptions $options = null)
	{
		// For internal use
		if (null === $component)
		{
			return;
		}
		if (null === $options)
		{
			$options = new MetaOptions();
		}

		// TODO Use adapter here
		// TODO Abstract from component meta, so other kinds of meta extractors could be used
		// For example, for development annotation based extractor could be used, which could compile
		// Metadata to arrays, and for production environment, compiled arrays could be used
		$annotations = [];
		$mes = [];

		// Get reflection data
		$ad = new Addendum();
		$ad->addNamespaces($options->namespaces);
		$info = $ad->annotate($component);

//		$info = Yii::app()->addendum->annotate($component);


		if (!$info instanceof ReflectionAnnotatedClass)
		{
			throw new Exception(sprintf('Could not annotate `%s`', get_class($component)));
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
		 * __get and __set should distinguish it somehow... - maybe by field type EComponentMetaProperty for fieltds etc.
		 * Currently disabled
		 * OR add function to Annotation to setEntity, which should point to _field, _main or _method?
		 */
		// Setup class annotations
		$this->_type = new $options->typeClass($info);
		foreach ($info->getAllAnnotations() as $annotation)
		{
			if (!$annotation instanceof IMetaAnnotation)
			{
				continue;
			}
			$annotation->setName($info->name);
			$annotation->setEntity($this->_type);
			$annotation->setMeta($this);
			$annotation->setComponent($component);
			$annotation->init();
			$annotations[] = $annotation;
		}
		// Setup methods
		foreach ($methods as $method)
		{
			if (!$method instanceof ReflectionAnnotatedMethod)
			{
				throw new Exception(sprintf('Could not annotate `%s::%s()`', get_class($component), $method->name));
			}
			if (IgnoredChecker::check($method))
			{
				continue;
			}
			$methodMeta = new $options->methodClass($method);
			foreach ($method->getAllAnnotations() as $annotation)
			{
				if (!$annotation instanceof IMetaAnnotation)
				{
					continue;
				}

				$annotation->setName($method->name);
				$annotation->setEntity($methodMeta);
				$annotation->setMeta($this);
				$annotation->setComponent($component);
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
				throw new Exception(sprintf('Could not annotate `%s::%s`', get_class($component), $property->name));
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
				if (!$annotation instanceof IMetaAnnotation)
				{
					continue;
				}

				$annotation->setName($field->name);
				$annotation->setEntity($field);
				$annotation->setMeta($this);
				$annotation->setComponent($component);
				$annotation->init();
				$annotations[] = $annotation;
			}
		}
		foreach ($annotations as $annotation)
		{
			$annotation->afterInit();
		}
	}

	public static function __set_state($data)
	{
		$obj = new self(null);
		foreach ($data as $field => $value)
		{
			$obj->$field = $value;
		}
		return $obj;
	}

	/**
	 * Create flyghtweight instace of EComponentMeta
	 * @param IAnnotated $component
	 * @return Meta
	 */
	public static function create(IAnnotated $component, MetaOptions $options = null)
	{
		$id = get_class($component);
		$class = get_called_class();
		if ($options && $options->namespaces)
		{
			foreach ($options->namespaces as $ns)
			{
				NameNormalizer::normalize($ns);
				if (!isset(self::$_ns[$class][$ns]))
				{
					self::$_ns[$class][$ns] = true;
					unset(self::$_instances[$class][$id]);
				}
			}
		}
		if (!isset(self::$_instances[$class][$id]))
		{
			$cache = self::_cacheGet($id);
			if ($cache)
			{
				self::$_instances[$class][$id] = $cache;
			}
			else
			{
				self::$_instances[$class][$id] = new static($component, $options);
				self::_cacheSet($id, self::$_instances[$class][$id]);
			}
		}

		return self::$_instances[$class][$id];
	}

	/**
	 * Apply initialization routines to concrete model instance
	 * @todo This should fire event, which for which annotations could intercept
	 * @deprecated since version number
	 * @param IAnnotated $component
	 */
	public function initModel(IAnnotated $component)
	{
		throw new \Exception('Deprecated method call');
		foreach ($this->_fields as $field)
		{
			// Unset fields which are accessed by get/set
			if (!$field->direct)
			{
				unset($component->{$field->name});
			}
		}
		return $this;
	}

	/**
	 * Get array of properties values for property field
	 *
	 * @param string $fieldName
	 * @param enum $type type of entities to return EComponentMeta::Type|EComponentMeta::Field|EComponentMeta::Method
	 * @return type
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

	public static function clearCache()
	{
		self::$_instances = [];
//		if (isset(Yii::app()->cache))
//		{
//			Yii::app()->cache->flush();
//		}
	}

	/**
	 * Get cache key
	 * @param string $id evaluated class name
	 * @return string
	 */
	private static function _getCacheKey($id)
	{
		return sprintf('annotation-v%s-%s-%s', getmyinode(), get_called_class(), $id);
	}

	/**
	 * Try to get metadata from cache
	 * @param string $id
	 * @return Meta|boolean
	 */
	private static function _cacheGet($id)
	{
//		$path = sprintf('%s/%s.php', Yii::app()->runtimePath, self::_getCacheKey($id));
//		if(file_exists($path))
//		{
//			return include $path;
//		}
//		return false;
//		if (!isset(Yii::app()->cache))
//		{
//			return false;
//		}
//		return Yii::app()->cache->get(self::_getCacheKey($id));
	}

	/**
	 * Set instance data to cache
	 * @param string $id
	 * @param Meta $value
	 * @return Meta|boolean
	 */
	private static function _cacheSet($id, Meta $value = null)
	{
//		if (!isset(Yii::app()->cache))
//		{
//			return false;
//		}
//		if (YII_DEBUG)
//		{
//			$path = sprintf('%s/%s.php', Yii::app()->runtimePath, self::_getCacheKey($id));
//			file_put_contents($path, sprintf("<?php\n%s;", var_export($value, true)));
//		}
//		return Yii::app()->cache->set(self::_getCacheKey($id), $value);
	}

}
