<?php

/**
 * Interface for {@see EComponentMeta} annotations container
 * @author Piotr
 */
interface IComponentMetaAnnotation
{

	/**
	 * Set metada class to be accessible for annotation for init etc. methods
	 * @param EComponentMeta $meta
	 */
	public function setMeta(EComponentMeta $meta);

	/**
	 * Set annotatins entity, it can be either class, property, or method
	 * @param IAnnotationEntity $entity
	 */
	public function setEntity(IAnnotationEntity $entity);

	/**
	 * This function should be called after all annotations are initialized.
	 * Any code that depends on other annotations can be executed here.
	 * NOTE: This is not ensured to run, its annotations container responsibility to call it.
	 */
	public function afterInit();
}
