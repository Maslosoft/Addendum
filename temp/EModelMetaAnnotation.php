<?php

/**
 * Annotation used for MModelMeta
 *
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

	public function setMeta(MModelMeta $meta)
	{
		$this->_meta = $meta;
	}
}