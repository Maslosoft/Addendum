<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CRegularExpressionValidator.
 */
/**
 * CRegularExpressionValidator validates that the attribute value matches to the specified {@link pattern regular expression}.
 * You may invert the validation logic with help of the {@link not} property (available since 1.1.5).
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CRegularExpressionValidator.php 3120 2011-03-25 01:50:48Z qiang.xue $
 * @package system.validators
 * @since 1.0
 */
class RegularExpressionValidatorAnnotation extends EValidatorAnnotation
{
	/**
	 * @var string the regular expression to be matched with
	 */
	public $pattern = NULL;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
	 * the regular expression defined via {@link pattern} should NOT match the attribute value.
	 * @since 1.1.5
	 **/
	public $not = false;

}