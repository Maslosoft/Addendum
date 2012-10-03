<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CUrlValidator.
 */
/**
 * CUrlValidator validates that the attribute value is a valid http or https URL.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class UrlValidatorAnnotation extends EValidatorAnnotation implements IBuiltInValidatorAnnotation
{
	/**
	 * @var string the regular expression used to validate the attribute value.
	 * Since version 1.1.7 the pattern may contain a {schemes} token that will be replaced
	 * by a regular expression which represents the {@see validSchemes}.
	 */
	public $pattern = '/^{schemes}:\\/\\/(([A-Z0-9][A-Z0-9_-]*)(\\.[A-Z0-9][A-Z0-9_-]*)+)/i';

	/**
	 * @var array list of URI schemes which should be considered valid. By default, http and https
	 * are considered to be valid schemes.
	 * @since 1.1.7
	 **/
	public $validSchemes = array (
  0 => 'http',
  1 => 'https',
);

	/**
	 * @var string the default URI scheme. If the input doesn't contain the scheme part, the default
	 * scheme will be prepended to it (thus changing the input). Defaults to null, meaning a URL must
	 * contain the scheme part.
	 * @since 1.1.7
	 **/
	public $defaultScheme = NULL;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

}