<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CDefaultValueValidator.
 */
/**
 * CDefaultValueValidator sets the attributes with the specified value.
 * It does not do validation. Its existence is mainly to allow
 * specifying attribute default values in a dynamic way.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CDefaultValueValidator.php 3515 2011-12-28 12:29:24Z mdomba $
 * @package system.validators
 */
class DefaultValueValidatorAnnotation extends EValidatorAnnotation
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