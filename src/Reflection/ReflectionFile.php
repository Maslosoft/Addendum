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

namespace Maslosoft\Addendum\Reflection;

use Maslosoft\Addendum\Builder\DocComment;
use ReflectionExtension;
use ReflectionMethod;
use ReflectionProperty;
use Reflector;
use UnexpectedValueException;

/**
 * ReflectionFile
 * TODO Stubbed reflection class for file, without including this file
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ReflectionFile implements Reflector
{

	const IS_IMPLICIT_ABSTRACT = 16;
	const IS_EXPLICIT_ABSTRACT = 32;
	const IS_FINAL = 64;

	/**
	 * Class anme
	 * @var string
	 */
	public $name = '';

	/**
	 * Extracted docs
	 * @var mixed[]
	 */
	private $_docs = [];
	private $namespace;
	private $shortName;
	private $file;
	private $methods;
	private $fields;

	/**
	 * (PHP 5)<br/>
	 * Constructs a ReflectionClass from file
	 * @link http://php.net/manual/en/reflectionclass.construct.php
	 * @param string $file <p>
	 * Either a string containing the name of the class to
	 * reflect, or an object.
	 * </p>
	 */
	public function __construct($file)
	{
		$docExtractor = new DocComment();
		$this->_docs = $docExtractor->forFile($file);
		$this->file = $file;
		$this->methods = $this->_docs['methods'];
		$this->fields = $this->_docs['fields'];

		$this->namespace = $this->_docs['namespace'];
		$this->shortName = $this->_docs['className'];
		if (empty($this->shortName))
		{
			throw new UnexpectedValueException(sprintf("Could not find any class in file `%s`", $file));
		}
		if (is_array($this->shortName))
		{
			throw new UnexpectedValueException(sprintf("`%s` does not support multiple classes. Found in file `%s`", __CLASS__, $file));
		}
		$this->name = $this->namespace . '\\' . $this->shortName;
	}

	final private function __clone()
	{
		
	}

	public function __toString()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Exports a class
	 * @link http://php.net/manual/en/reflectionclass.export.php
	 * @param mixed $argument <p>
	 * The reflection to export.
	 * </p>
	 * @param bool $return [optional] <p>
	 * Setting to <b>TRUE</b> will return the export,
	 * as opposed to emitting it. Setting to <b>FALSE</b> (the default) will do the opposite.
	 * </p>
	 * @return string If the <i>return</i> parameter
	 * is set to <b>TRUE</b>, then the export is returned as a string,
	 * otherwise <b>NULL</b> is returned.
	 */
	public static function export()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets class name
	 * @link http://php.net/manual/en/reflectionclass.getname.php
	 * @return string The class name.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if class is defined internally by an extension, or the core
	 * @link http://php.net/manual/en/reflectionclass.isinternal.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isInternal()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if user defined
	 * @link http://php.net/manual/en/reflectionclass.isuserdefined.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isUserDefined()
	{
		return true;
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if the class is instantiable
	 * @link http://php.net/manual/en/reflectionclass.isinstantiable.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isInstantiable()
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Returns whether this class is cloneable
	 * @link http://php.net/manual/en/reflectionclass.iscloneable.php
	 * @return bool <b>TRUE</b> if the class is cloneable, <b>FALSE</b> otherwise.
	 */
	public function isCloneable()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets the filename of the file in which the class has been defined
	 * @link http://php.net/manual/en/reflectionclass.getfilename.php
	 * @return string the filename of the file in which the class has been defined.
	 * If the class is defined in the PHP core or in a PHP extension, <b>FALSE</b>
	 * is returned.
	 */
	public function getFileName()
	{
		return $this->file;
	}

	/**
	 * (PHP 5)<br/>
	 * Gets starting line number
	 * @link http://php.net/manual/en/reflectionclass.getstartline.php
	 * @return int The starting line number, as an integer.
	 */
	public function getStartLine()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets end line
	 * @link http://php.net/manual/en/reflectionclass.getendline.php
	 * @return int The ending line number of the user defined class, or <b>FALSE</b> if unknown.
	 */
	public function getEndLine()
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Gets doc comments
	 * @link http://php.net/manual/en/reflectionclass.getdoccomment.php
	 * @return string The doc comment if it exists, otherwise <b>FALSE</b>
	 */
	public function getDocComment()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets the constructor of the class
	 * @link http://php.net/manual/en/reflectionclass.getconstructor.php
	 * @return ReflectionMethod A <b>ReflectionMethod</b> object reflecting the class' constructor, or <b>NULL</b> if the class
	 * has no constructor.
	 */
	public function getConstructor()
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Checks if method is defined
	 * @link http://php.net/manual/en/reflectionclass.hasmethod.php
	 * @param string $name <p>
	 * Name of the method being checked for.
	 * </p>
	 * @return bool <b>TRUE</b> if it has the method, otherwise <b>FALSE</b>
	 */
	public function hasMethod($name)
	{
		return array_key_exists($name, $this->methods);
	}

	/**
	 * (PHP 5)<br/>
	 * Gets a <b>ReflectionMethod</b> for a class method.
	 * @link http://php.net/manual/en/reflectionclass.getmethod.php
	 * @param string $name <p>
	 * The method name to reflect.
	 * </p>
	 * @return ReflectionMethod A <b>ReflectionMethod</b>.
	 */
	public function getMethod($name)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets an array of methods
	 * @link http://php.net/manual/en/reflectionclass.getmethods.php
	 * @param int $filter [optional] <p>
	 * Filter the results to include only methods with certain attributes. Defaults
	 * to no filtering.
	 * </p>
	 * <p>
	 * Any combination of <b>ReflectionMethod::IS_STATIC</b>,
	 * <b>ReflectionMethod::IS_PUBLIC</b>,
	 * <b>ReflectionMethod::IS_PROTECTED</b>,
	 * <b>ReflectionMethod::IS_PRIVATE</b>,
	 * <b>ReflectionMethod::IS_ABSTRACT</b>,
	 * <b>ReflectionMethod::IS_FINAL</b>.
	 * </p>
	 * @return array An array of <b>ReflectionMethod</b> objects
	 * reflecting each method.
	 */
	public function getMethods($filter = null)
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Checks if property is defined
	 * @link http://php.net/manual/en/reflectionclass.hasproperty.php
	 * @param string $name <p>
	 * Name of the property being checked for.
	 * </p>
	 * @return bool <b>TRUE</b> if it has the property, otherwise <b>FALSE</b>
	 */
	public function hasProperty($name)
	{
		return array_key_exists($name, $this->fields);
	}

	/**
	 * (PHP 5)<br/>
	 * Gets a <b>ReflectionProperty</b> for a class's property
	 * @link http://php.net/manual/en/reflectionclass.getproperty.php
	 * @param string $name <p>
	 * The property name.
	 * </p>
	 * @return ReflectionProperty A <b>ReflectionProperty</b>.
	 */
	public function getProperty($name)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets properties
	 * @link http://php.net/manual/en/reflectionclass.getproperties.php
	 * @param int $filter [optional] <p>
	 * The optional filter, for filtering desired property types. It's configured using
	 * the ReflectionProperty constants,
	 * and defaults to all property types.
	 * </p>
	 * @return array An array of <b>ReflectionProperty</b> objects.
	 */
	public function getProperties($filter = null)
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Checks if constant is defined
	 * @link http://php.net/manual/en/reflectionclass.hasconstant.php
	 * @param string $name <p>
	 * The name of the constant being checked for.
	 * </p>
	 * @return bool <b>TRUE</b> if the constant is defined, otherwise <b>FALSE</b>.
	 */
	public function hasConstant($name)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets constants
	 * @link http://php.net/manual/en/reflectionclass.getconstants.php
	 * @return array An array of constants.
	 * Constant name in key, constant value in value.
	 */
	public function getConstants()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets defined constant
	 * @link http://php.net/manual/en/reflectionclass.getconstant.php
	 * @param string $name <p>
	 * Name of the constant.
	 * </p>
	 * @return mixed Value of the constant.
	 */
	public function getConstant($name)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets the interfaces
	 * @link http://php.net/manual/en/reflectionclass.getinterfaces.php
	 * @return array An associative array of interfaces, with keys as interface
	 * names and the array values as <b>ReflectionClass</b> objects.
	 */
	public function getInterfaces()
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.2.0)<br/>
	 * Gets the interface names
	 * @link http://php.net/manual/en/reflectionclass.getinterfacenames.php
	 * @return array A numerical array with interface names as the values.
	 */
	public function getInterfaceNames()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if the class is an interface
	 * @link http://php.net/manual/en/reflectionclass.isinterface.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isInterface()
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Returns an array of traits used by this class
	 * @link http://php.net/manual/en/reflectionclass.gettraits.php
	 * @return array an array with trait names in keys and instances of trait's
	 * <b>ReflectionClass</b> in values.
	 * Returns <b>NULL</b> in case of an error.
	 */
	public function getTraits()
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Returns an array of names of traits used by this class
	 * @link http://php.net/manual/en/reflectionclass.gettraitnames.php
	 * @return array an array with trait names in values.
	 * Returns <b>NULL</b> in case of an error.
	 */
	public function getTraitNames()
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Returns an array of trait aliases
	 * @link http://php.net/manual/en/reflectionclass.gettraitaliases.php
	 * @return array an array with new method names in keys and original names (in the
	 * format "TraitName::original") in values.
	 * Returns <b>NULL</b> in case of an error.
	 */
	public function getTraitAliases()
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Returns whether this is a trait
	 * @link http://php.net/manual/en/reflectionclass.istrait.php
	 * @return bool <b>TRUE</b> if this is a trait, <b>FALSE</b> otherwise.
	 * Returns <b>NULL</b> in case of an error.
	 */
	public function isTrait()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if class is abstract
	 * @link http://php.net/manual/en/reflectionclass.isabstract.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isAbstract()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if class is final
	 * @link http://php.net/manual/en/reflectionclass.isfinal.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isFinal()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets modifiers
	 * @link http://php.net/manual/en/reflectionclass.getmodifiers.php
	 * @return int bitmask of
	 * modifier constants.
	 */
	public function getModifiers()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks class for instance
	 * @link http://php.net/manual/en/reflectionclass.isinstance.php
	 * @param object $object <p>
	 * The object being compared to.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isInstance($object)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Creates a new class instance from given arguments.
	 * @link http://php.net/manual/en/reflectionclass.newinstance.php
	 * @param mixed $args <p>
	 * Accepts a variable number of arguments which are passed to the class
	 * constructor, much like <b>call_user_func</b>.
	 * </p>
	 * @param mixed $_ [optional]
	 * @return object
	 */
	public function newInstance($args, $_ = null)
	{
		
	}

	/**
	 * (PHP &gt;= 5.4.0)<br/>
	 * Creates a new class instance without invoking the constructor.
	 * @link http://php.net/manual/en/reflectionclass.newinstancewithoutconstructor.php
	 * @return object
	 */
	public function newInstanceWithoutConstructor()
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.3)<br/>
	 * Creates a new class instance from given arguments.
	 * @link http://php.net/manual/en/reflectionclass.newinstanceargs.php
	 * @param array $args [optional] <p>
	 * The parameters to be passed to the class constructor as an array.
	 * </p>
	 * @return object a new instance of the class.
	 */
	public function newInstanceArgs(array $args = null)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets parent class
	 * @link http://php.net/manual/en/reflectionclass.getparentclass.php
	 * @return object A <b>ReflectionClass</b>.
	 */
	public function getParentClass()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if a subclass
	 * @link http://php.net/manual/en/reflectionclass.issubclassof.php
	 * @param string $class <p>
	 * The class name being checked against.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isSubclassOf($class)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets static properties
	 * @link http://php.net/manual/en/reflectionclass.getstaticproperties.php
	 * @return array The static properties, as an array.
	 */
	public function getStaticProperties()
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Gets static property value
	 * @link http://php.net/manual/en/reflectionclass.getstaticpropertyvalue.php
	 * @param string $name <p>
	 * The name of the static property for which to return a value.
	 * </p>
	 * @param mixed $def_value [optional] <p>
	 * </p>
	 * @return mixed The value of the static property.
	 */
	public function getStaticPropertyValue($name, &$def_value = null)
	{
		
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Sets static property value
	 * @link http://php.net/manual/en/reflectionclass.setstaticpropertyvalue.php
	 * @param string $name <p>
	 * Property name.
	 * </p>
	 * @param string $value <p>
	 * New property value.
	 * </p>
	 * @return void No value is returned.
	 */
	public function setStaticPropertyValue($name, $value)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets default properties
	 * @link http://php.net/manual/en/reflectionclass.getdefaultproperties.php
	 * @return array An array of default properties, with the key being the name of
	 * the property and the value being the default value of the property or <b>NULL</b>
	 * if the property doesn't have a default value. The function does not distinguish
	 * between static and non static properties and does not take visibility modifiers
	 * into account.
	 */
	public function getDefaultProperties()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Checks if iterateable
	 * @link http://php.net/manual/en/reflectionclass.isiterateable.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function isIterateable()
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Implements interface
	 * @link http://php.net/manual/en/reflectionclass.implementsinterface.php
	 * @param string $interface <p>
	 * The interface name.
	 * </p>
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function implementsInterface($interface)
	{
		
	}

	/**
	 * (PHP 5)<br/>
	 * Gets a <b>ReflectionExtension</b> object for the extension which defined the class
	 * @link http://php.net/manual/en/reflectionclass.getextension.php
	 * @return ReflectionExtension A <b>ReflectionExtension</b> object representing the extension which defined the class,
	 * or <b>NULL</b> for user-defined classes.
	 */
	public function getExtension()
	{
		return null;
	}

	/**
	 * (PHP 5)<br/>
	 * Gets the name of the extension which defined the class
	 * @link http://php.net/manual/en/reflectionclass.getextensionname.php
	 * @return string The name of the extension which defined the class, or <b>FALSE</b> for user-defined classes.
	 */
	public function getExtensionName()
	{
		return false;
	}

	/**
	 * (PHP 5 &gt;= 5.3.0)<br/>
	 * Checks if in namespace
	 * @link http://php.net/manual/en/reflectionclass.innamespace.php
	 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
	 */
	public function inNamespace()
	{
		return strlen($this->namespace) > 1;
	}

	/**
	 * (PHP 5 &gt;= 5.3.0)<br/>
	 * Gets namespace name
	 * @link http://php.net/manual/en/reflectionclass.getnamespacename.php
	 * @return string The namespace name.
	 */
	public function getNamespaceName()
	{
		return $this->namespace;
	}

	/**
	 * (PHP 5 &gt;= 5.3.0)<br/>
	 * Gets short name
	 * @link http://php.net/manual/en/reflectionclass.getshortname.php
	 * @return string The class short name.
	 */
	public function getShortName()
	{
		return $this->shortName;
	}

}
