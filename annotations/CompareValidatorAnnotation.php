<?php

use Maslosoft\Addendum\Base\ValidatorAnnotation;
use Maslosoft\Addendum\Interfaces\IBuiltInValidatorAnnotation;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CCompareValidator.
 */

/**
 * CCompareValidator compares the specified attribute value with another value and validates if they are equal.
 *
 * The value being compared with can be another attribute value
 * (specified via {@link compareAttribute}) or a constant (specified via
 * {@link compareValue}. When both are specified, the latter takes
 * precedence. If neither is specified, the attribute will be compared
 * with another attribute whose name is by appending "_repeat" to the source
 * attribute name.
 *
 * The comparison can be either {@link strict} or not.
 *
 * CCompareValidator supports different comparison operators.
 * Previously, it only compares to see if two values are equal or not.
 *
 * When using the {@link message} property to define a custom error message, the message
 * may contain additional placeholders that will be replaced with the actual content. In addition
 * to the "{attribute}" placeholder, recognized by all validators (see {@link CValidator}),
 * CCompareValidator allows for the following placeholders to be specified:
 * <ul>
 * <li>{compareValue}: replaced with the constant value being compared with {@link compareValue}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class CompareValidatorAnnotation extends ValidatorAnnotation implements IBuiltInValidatorAnnotation
{

	/**
	 * @var string the name of the attribute to be compared with
	 */
	public $compareAttribute = NULL;

	/**
	 * @var string the constant value to be compared with
	 */
	public $compareValue = NULL;

	/**
	 * @var boolean whether the comparison is strict (both value and type must be the same.)
	 * Defaults to false.
	 */
	public $strict = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to false.
	 * If this is true, it means the attribute is considered valid when it is empty.
	 */
	public $allowEmpty = false;

	/**
	 * @var string the operator for comparison. Defaults to '='.
	 * The followings are valid operators:
	 * <ul>
	 * <li>'=' or '==': validates to see if the two values are equal. If {@link strict} is true, the comparison
	 * will be done in strict mode (i.e. checking value type as well).</li>
	 * <li>'!=': validates to see if the two values are NOT equal. If {@link strict} is true, the comparison
	 * will be done in strict mode (i.e. checking value type as well).</li>
	 * <li>'>': validates to see if the value being validated is greater than the value being compared with.</li>
	 * <li>'>=': validates to see if the value being validated is greater than or equal to the value being compared with.</li>
	 * <li>'<': validates to see if the value being validated is less than the value being compared with.</li>
	 * <li>'<=': validates to see if the value being validated is less than or equal to the value being compared with.</li>
	 * </ul>
	 */
	public $operator = '=';

}
