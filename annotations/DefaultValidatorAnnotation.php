<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CDefaultValidator.
 */
/**
 * CDefaultValueValidator sets the attributes with the specified value.
 * It does not do validation but rather allows setting a default value at the
 * same time validation is performed. Usually this happens when calling either
 * <code>$model->validate()</code> or <code>$model->save()</code>.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 */
class DefaultValidatorAnnotation extends EValidatorAnnotation implements IBuiltInValidatorAnnotation
{
	/**
	 * @var mixed the default value to be set to the specified attributes.
	 */
	public $value = NULL;

	/**
	 * @var boolean whether to set the default value only when the attribute value is null or empty string.
	 * Defaults to true. If false, the attribute will always be assigned with the default value,
	 * even if it is already explicitly assigned a value.
	 */
	public $setOnEmpty = true;

}