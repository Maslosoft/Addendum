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

namespace Maslosoft\Addendum\Collections;

use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Interfaces\AnnotationEntityInterface;
use Maslosoft\Addendum\Interfaces\MetaAnnotationInterface;

/**
 * Annotation used for Collections\Meta
 * @author Piotr
 */
abstract class MetaAnnotation extends Annotation implements MetaAnnotationInterface
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
	 * @var AnnotationEntityInterface
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
	 * @param AnnotationEntityInterface $entity
	 */
	public function setEntity(AnnotationEntityInterface $entity)
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
