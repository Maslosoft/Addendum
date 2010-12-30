<?php

/**
 * Container for class annotations
 * @copyright copyright
 * @license licence
 * @author Piotr Maselkowski <piotr at maselkowski dot pl>
 */
class EAnnotationClass extends CComponent implements ArrayAccess, Countable, Iterator
{
	public $attributtes;
	public $fields;
	public $methods;
	
	/**
	 * @var EAnnotations
	 */
	protected $_annotations = null;
	
	/**
	 * Context of annotation
	 * @var ReflectionClass
	 */
	protected $_reflection = null;

	public function __construct(EAnnotations $annotations, $reflection)
	{
		$this->_annotations = $annotations;
		$this->_reflection = $reflection;
		$this->attributtes = new stdClass();
		$this->fields = new stdClass();
		$this->methods = new stdClass();
	}

	public function addTag($name, $value)
	{
		if($this->_annotations->hasTag('class', $name))
		{
			$className = Yii::import($this->_annotations->tags['class'][$name]);
			$attr = new $className($this->_reflection, $value);
			if($attr->isUnique)
			{
				$this->attributtes->$name = $attr;
			}
			else
			{
				array_push($this->attributtes->$name, $attr);
			}
		}
	}

	public function count()
	{
		return count($this->attributtes);
	}

	public function key()
	{
		return key($this->attributes);
	}

	public function current()
	{
		return current($this->attributes);
	}

	public function next()
	{

	}

	public function offsetExists($offset)
	{

	}

	public function offsetGet($offset)
	{

	}

	public function offsetSet($offset, $value)
	{

	}

	public function offsetUnset($offset)
	{

	}

	public function rewind()
	{

	}

	public function valid()
	{

	}
}