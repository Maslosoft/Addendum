<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CStringValidator.
 */
/**
 * CStringValidator validates that the attribute value is of certain length.
 *
 * Note, this validator should only be used with string-typed attributes.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CStringValidator has a couple custom error messages you can set that correspond to different
 * validation scenarios. For defining a custom message when the string is too short, 
 * you may use the {@link tooShort} property. Similarly with {@link tooLong}. The messages may contain 
 * placeholders that will be replaced with the actual content. In addition to the "{attribute}" 
 * placeholder, recognized by all validators (see {@link CValidator}), CStringValidator allows for the following
 * placeholders to be specified:
 * <ul>
 * <li>{min}: when using {@link tooShort}, replaced with minimum length, {@link min}, if set.</li>
 * <li>{max}: when using {@link tooLong}, replaced with the maximum length, {@link max}, if set.</li>
 * <li>{length}: when using {@link message}, replaced with the exact required length, {@link is}, if set.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CStringValidator.php 3491 2011-12-17 05:17:57Z jefftulsa $
 * @package system.validators
 * @since 1.0
 */
class StringValidatorAnnotation extends EValidatorAnnotation
{
	/**
	 * @var integer maximum length. Defaults to null, meaning no maximum limit.
	 */
	public $max = NULL;

	/**
	 * @var integer minimum length. Defaults to null, meaning no minimum limit.
	 */
	public $min = NULL;

	/**
	 * @var integer exact length. Defaults to null, meaning no exact length limit.
	 */
	public $is = NULL;

	/**
	 * @var string user-defined error message used when the value is too short.
	 */
	public $tooShort = NULL;

	/**
	 * @var string user-defined error message used when the value is too long.
	 */
	public $tooLong = NULL;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var string the encoding of the string value to be validated (e.g. 'UTF-8').
	 * This property is used only when mbstring PHP extension is enabled.
	 * The value of this property will be used as the 2nd parameter of the
	 * mb_strlen() function. If this property is not set, the application charset
	 * will be used.
	 * If this property is set false, then strlen() will be used even if mbstring is enabled.
	 * @since 1.1.1
	 */
	public $encoding = NULL;

}