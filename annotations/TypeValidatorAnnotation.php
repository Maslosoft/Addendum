<?php

use Maslosoft\Addendum\Base\ValidatorAnnotation;
use Maslosoft\Addendum\Interfaces\IBuiltInValidatorAnnotation;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CTypeValidator.
 */

/**
 * CTypeValidator verifies if the attribute is of the type specified by {@link type}.
 *
 * The following data types are supported:
 * <ul>
 * <li><b>integer</b> A 32-bit signed integer data type.</li>
 * <li><b>float</b> A double-precision floating point number data type.</li>
 * <li><b>string</b> A string data type.</li>
 * <li><b>array</b> An array value. </li>
 * <li><b>date</b> A date data type.</li>
 * <li><b>time</b> A time data type.</li>
 * <li><b>datetime</b> A date and time data type.</li>
 * </ul>
 *
 * For <b>date</b> type, the property {@link dateFormat}
 * will be used to determine how to parse the date string. If the given date
 * value doesn't follow the format, the attribute is considered as invalid.
 *
 * Starting from version 1.1.7, we have a dedicated date validator {@link CDateValidator}.
 * Please consider using this validator to validate a date-typed value.
 *
 * When using the {@link message} property to define a custom error message, the message
 * may contain additional placeholders that will be replaced with the actual content. In addition
 * to the "{attribute}" placeholder, recognized by all validators (see {@link CValidator}),
 * CTypeValidator allows for the following placeholders to be specified:
 * <ul>
 * <li>{type}: replaced with data type the attribute should be {@link type}.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class TypeValidatorAnnotation extends ValidatorAnnotation implements IBuiltInValidatorAnnotation
{

	/**
	 * @var string the data type that the attribute should be. Defaults to 'string'.
	 * Valid values include 'string', 'integer', 'float', 'array', 'date', 'time' and 'datetime'.
	 */
	public $type = 'string';

	/**
	 * @var string the format pattern that the date value should follow. Defaults to 'MM/dd/yyyy'.
	 * Please see {@link CDateTimeParser} for details about how to specify a date format.
	 * This property is effective only when {@link type} is 'date'.
	 */
	public $dateFormat = 'MM/dd/yyyy';

	/**
	 * @var string the format pattern that the time value should follow. Defaults to 'hh:mm'.
	 * Please see {@link CDateTimeParser} for details about how to specify a time format.
	 * This property is effective only when {@link type} is 'time'.
	 */
	public $timeFormat = 'hh:mm';

	/**
	 * @var string the format pattern that the datetime value should follow. Defaults to 'MM/dd/yyyy hh:mm'.
	 * Please see {@link CDateTimeParser} for details about how to specify a datetime format.
	 * This property is effective only when {@link type} is 'datetime'.
	 */
	public $datetimeFormat = 'MM/dd/yyyy hh:mm';

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

}
