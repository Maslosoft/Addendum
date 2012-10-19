<?php

/**
 * Base class for validator annotations
 *
 * @author Piotr
 */
abstract class EValidatorAnnotation extends EModelMetaAnnotation
{
	/**
	 * @var string the user-defined error message. Different validators may define various
	 * placeholders in the message that are to be replaced with actual values. All validators
	 * recognize "{attribute}" placeholder, which will be replaced with the label of the attribute.
	 */
	public $message;

	/**
	 * @var boolean whether this validation rule should be skipped when there is already a validation
	 * error for the current attribute. Defaults to false.
	 * @since 1.1.1
	 */
	public $skipOnError = false;

	/**
	 * @var array list of scenarios that the validator should be applied.
	 * Each array value refers to a scenario name with the same name as its array key.
	 */
	public $on;

	/**
	 * @var boolean whether attributes listed with this validator should be considered safe for massive assignment.
	 * Defaults to true.
	 * @since 1.1.4
	 */
	public $safe = true;

	/**
	 * @var boolean whether to perform client-side validation. Defaults to true.
	 * Please refer to {@link CActiveForm::enableClientValidation} for more details about client-side validation.
	 * @since 1.1.7
	 */
	public $enableClientValidation = true;

	/**
	 * @var array list of scenarios that the validator should not be applied to.
	 * Each array value refers to a scenario name with the same name as its array key.
	 * @since 1.1.11
	 */
	public $except = NULL;

	public function init()
	{
		$name = preg_replace('~Annotation$~', '', lcfirst(get_class($this)));
		$value = array_intersect_key((array)$this, array_flip($this->_publicProperties));
		unset($value['name']);
		$this->_entity->$name = $value;
	}
}