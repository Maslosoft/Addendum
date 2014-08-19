<?php

use Maslosoft\Addendum\Base\ValidatorAnnotation;
use Maslosoft\Addendum\Interfaces\IBuiltInValidatorAnnotation;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CNumericalValidator.
 */

/**
 * CNumberValidator validates that the attribute value is a number.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CNumberValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. To specify a custom message when the numeric value is too big, 
 * you may use the {@link tooBig} property. Similarly with {@link tooSmall}.
 * The messages may contain additional placeholders that will be replaced 
 * with the actual content. In addition to the "{attribute}" placeholder, recognized by all 
 * validators (see {@link CValidator}), CNumberValidator allows for the following placeholders 
 * to be specified:
 * <ul>
 * <li>{min}: when using {@link tooSmall}, replaced with the lower limit of the number {@link min}.</li>
 * <li>{max}: when using {@link tooBig}, replaced with the upper limit of the number {@link max}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class NumericalValidatorAnnotation extends ValidatorAnnotation implements IBuiltInValidatorAnnotation
{

	/**
	 * @var boolean whether the attribute value can only be an integer. Defaults to false.
	 */
	public $integerOnly = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var integer|float upper limit of the number. Defaults to null, meaning no upper limit.
	 */
	public $max = NULL;

	/**
	 * @var integer|float lower limit of the number. Defaults to null, meaning no lower limit.
	 */
	public $min = NULL;

	/**
	 * @var string user-defined error message used when the value is too big.
	 */
	public $tooBig = NULL;

	/**
	 * @var string user-defined error message used when the value is too small.
	 */
	public $tooSmall = NULL;

	/**
	 * @var string the regular expression for matching integers.
	 * @since 1.1.7
	 */
	public $integerPattern = '/^\\s*[+-]?\\d+\\s*$/';

	/**
	 * @var string the regular expression for matching numbers.
	 * @since 1.1.7
	 */
	public $numberPattern = '/^\\s*[-+]?[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?\\s*$/';

}
