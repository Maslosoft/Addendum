<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CDateValidator.
 */
/**
 * CDateValidator verifies if the attribute represents a date, time or datetime.
 *
 * By setting the {@link format} property, one can specify what format the date value
 * must be in. If the given date value doesn't follow the format, the attribute is considered as invalid.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id: CDateValidator.php 2799 2011-01-01 19:31:13Z qiang.xue $
 * @package system.validators
 * @since 1.1.7
 */
class DateValidatorAnnotation extends EValidatorAnnotation
{
	/**
	 * @var mixed the format pattern that the date value should follow.
	 * This can be either a string or an array representing multiple formats.
	 * Defaults to 'MM/dd/yyyy'. Please see {@link CDateTimeParser} for details
	 * about how to specify a date format.
	 */
	public $format = 'MM/dd/yyyy';

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * @var string the name of the attribute to receive the parsing result.
	 * When this property is not null and the validation is successful, the named attribute will
	 * receive the parsing result.
	 */
	public $timestampAttribute = NULL;

}