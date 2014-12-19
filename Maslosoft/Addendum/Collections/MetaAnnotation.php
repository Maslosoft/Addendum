<?php

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Interfaces\IAnnotationEntity;
use Maslosoft\Addendum\Interfaces\IMetaAnnotation;

/**
 * Annotation used for Collections\Meta
 * @author Piotr
 */
abstract class MetaAnnotation extends Annotation implements IMetaAnnotation
{

	/**
	 * Name of annotated field/method/class
	 * @var string
	 */
	public $name = '';

	/**
	 * Model metadata object
	 * @var Meta
	 */
	protected $_meta = null;

	/**
	 * Annotatins entity, it can be either class, property, or method
	 * Its conrete annotation implementation responsibility to decide what to do with it.
	 * @var IAnnotationEntity
	 */
	protected $_entity = null;

	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * Set metada class to be accessible for annotation for init etc. methods
	 * @param Meta $meta
	 */
	public function setMeta(Meta $meta)
	{
		$this->_meta = $meta;
	}

	/**
	 * Set annotatins entity, it can be either class, property, or method
	 * @param IAnnotationEntity $entity
	 */
	public function setEntity(IAnnotationEntity $entity)
	{
		$this->_entity = $entity;
	}

	/**
	 * This function should be called after all annotations are initialized.
	 * Any code that depends on other annotations can be executed here.
	 * NOTE: This is not ensured to run, its annotations container responsibility to call it.
	 */
	public function afterInit()
	{
		
	}

}
