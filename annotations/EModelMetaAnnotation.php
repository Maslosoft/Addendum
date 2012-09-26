<?php

/**
 * Annotation used for MModelMeta
 * @todo Move to parent directory
 * @author Piotr
 */
abstract class EModelMetaAnnotation extends EAnnotation
{
	/**
	 * Name of annotated field/method/class
	 * @var string
	 */
	public $name = '';

	/**
	 * Model metadata object
	 * @var MModelMeta
	 */
	protected $_meta = null;

	/**
	 * Annotatins entity, it can be either class, property, or method
	 * Its conrete annotation implementation responsibility to decide what to do with it.
	 * @var IAnnotationEntity
	 */
	protected $_entity = null;

	/**
	 * Set metada class to be accessible for annotation for init etc. methods
	 * @param MModelMeta $meta
	 */
	public function setMeta(MModelMeta $meta)
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