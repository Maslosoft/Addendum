<?php

/**
 * Mongo generator base class for doc comments processing
 *
 * @property string tagName
 * @property string contextNameMsg
 * @property string contextClass
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
abstract class EAnnotationAttr extends CComponent
{
	/**
	 * Attribute context: Class or Class Field reflection instance
	 * @var Reflector
	 */
	protected $_context;
	/**
	 * Value defined in tag
	 * @var string
	 */
	protected $_value;
	/**
	 * Tag name
	 * @var string
	 */
	private $_tagName = '';
	/**
	 * True if tag must be unique in single property/class
	 */
	private $_isUnique = true;
	/**
	 * True if property tag must be unique in entire class
	 * @var bool
	 */
	private $_isUniqueInClass = false;
	/**
	 * Array with class aliases to import for class ie. array('ClassName' => array('first.alias', 'second.alias'));
	 * @var array
	 */
	private static $_imports = array();
	/**
	 * Collection of all tags for all classes and fields
	 * @var array
	 */
	private static $_tags = array();
	/**
	 * Collection of all tags for class
	 * @var array
	 */
	private static $_classTags = array();

	public function __construct(Reflector $context = null, $attributeValue = null)
	{
		$this->_context = $context;
		$this->_value = $attributeValue;
		$this->_tagName = lcfirst(preg_replace('~Attr$~', '', get_class($this)));

		$this->setup();

		// If context and attributeValue is not set perform only setup
		if(null === $this->_context && null === $attributeValue)
		{
			return;
		}

		// Check if tag is used in proper context
		if($this->_context instanceof ReflectionClass && !$this instanceof MongoClassAttr)
		{
			throw new MongoException(sprintf('Tag "%s" must be used on class in %s', $this->_tagName, $this->getContextNameMsg()));
		}
		elseif($this->_context instanceof ReflectionProperty && !$this instanceof MongoFieldAttr)
		{
			throw new MongoException(sprintf('Tag "%s" must be used on class field in %s', $this->_tagName, $this->getContextNameMsg()));
		}
		else
		{
			throw new MongoException(sprintf('Processing of "%s" is not supported'), get_class($this->_context));
		}

		// Check uniqueness in context
		$key = $this->getContextName();
		if($this->isUnique)
		{
			if(isset(self::$_tags[$key]))
			{
				throw new MongoException(sprintf('Tag %s must be defined only once in %s', $this->_tagName, $this->getContextNameMsg()));
			}
			else
			{
				self::$_tags[$key] = $this;
			}
		}
		else
		{
			self::$_tags[$key][] = $this;
		}

		// Check uniqueness in class
		$key = $this->getContextClass();
		if($this->isUniqueInClass)
		{
			if(isset(self::$_classTags[$key]))
			{
				throw new MongoException(sprintf('Tag %s must be defined only once once in %s for class %s', $this->_tagName, $this->getContextNameMsg(), $this->getContextClass()));
			}
			else
			{
				self::$_classTags[$key] = $this;
			}
		}
		else
		{
			self::$_classTags[$key][] = $this;
		}
	}

	/**
	 * Add import to model
	 * @param string $alias
	 */
	public final function addImport($alias)
	{
		Yii::import($alias, true);
		if(false == class_exists(Yii::getClassName($alias)))
		{
			throw new MongoException(sprintf('Class "%s" not found in %s', $alias, $this->getContextNameMsg()));
		}
		$key = $this->getContextClass();
		self::$_imports[$key][] = $alias;
		self::$_imports[$key] = array_unique(self::$_imports[$key]);
	}

	/**
	 * Check if tag exists in current context
	 * @param string $tag
	 * @return bool
	 */
	public final function hasTag($tag)
	{
		$key = $this->getContextName();
		return isset(self::$_tags[$key]);
	}

	/**
	 * Get tag from current context or false if not exists
	 * @param string $tag
	 * @return MongoAttr or false if tag does not exists
	 */
	public final function getTag($tag)
	{
		if($this->hasTag($tag))
		{
			$key = $this->getContextName();
			return self::$_tags[$key];
		}
		return false;
	}

	/**
	 * Get required imports for model
	 * @return string[]
	 */
	public final function getImports()
	{
		$key = $this->getContextClass();
		return self::$_imports[$key];
	}

	/**
	 * Return true if its class context
	 * @return bool
	 */
	public final function getIsClass()
	{
		return $this instanceof MongoClassAttr;
	}

	/**
	 * Return true if its class field context
	 * @return bool
	 */
	public final function getIsField()
	{
		return $this instanceof MongoFieldAttr;
	}

	/**
	 * @return string
	 */
	public final function getTagName()
	{
		return $this->_tagName;
	}

	public final function setTagName()
	{
		throw new MongoException('Tag name is readonly');
	}

	/**
	 * @param bool $value
	 */
	public final function setIsUnique($value)
	{
		$this->_isUnique = (bool)$value;
	}

	/**
	 * @return bool
	 */
	public final function getIsUnique()
	{
		return $this->_isUnique;
	}

	/**
	 * @param bool $value
	 */
	public final function setIsUniqueInClass($value)
	{
		$this->_isUniqueInClass = (bool)$value;
	}

	/**
	 * @return bool
	 */
	public final function getIsUniqueInClass()
	{
		return $this->_isUniqueInClass;
	}

	/**
	 * Get context class name
	 * @return string
	 */
	public function getContextClass()
	{
		if($this->isClass)
		{
			return $this->_context->name;
		}
		elseif($this->isField)
		{
			return $this->_context->class;
		}
		else
		{
			throw new MongoException(sprintf('Unsupported context in %s', $this->getContextNameMsg()));
		}
	}

	/**
	 * Get context class name and optional field ie. SomeClass or SomeClass::someField
	 * @return string
	 */
	public function getContextName()
	{
		if($this->isClass)
		{
			return $this->getContextClass();
		}
		else
		{
			return $this->getContextClass() . '::' . $this->_context->name;
		}
	}

	/**
	 * Get context name for messages
	 * @return string
	 */
	public function getContextNameMsg()
	{
		if($this->isClass())
		{
			return sprintf(' tag "%s" class "%s" ', $this->getTagName(), $this->_context->name);
		}
		else
		{
			return sprintf(' tag "%s" property "%s" class "%s" ', $this->getTagName(), $this->_context->name, $this->_context->class);
		}
	}

	/**
	 * Put processing/initialization code in child classes
	 */
	abstract public function init();

	/**
	 * Setup attributes etc.
	 */
	public function setup()
	{
		
	}
}