<?php

/**
 * Annotation used for EComponentMeta
 * @author Piotr
 */
abstract class EComponentMetaAnnotation extends EAnnotation implements IComponentMetaAnnotation
{
	/**
	 * Name of annotated field/method/class
	 * @var string
	 */
	public $name = '';

	/**
	 * Model metadata object
	 * @var EComponentMeta
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
	 * @param EComponentMeta $meta
	 */
	public function setMeta(EComponentMeta $meta)
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