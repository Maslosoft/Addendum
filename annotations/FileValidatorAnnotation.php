<?php

use Maslosoft\Addendum\Base\ValidatorAnnotation;
use Maslosoft\Addendum\Interfaces\IBuiltInValidatorAnnotation;

/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see CFileValidator.
 */

/**
 * CFileValidator verifies if an attribute is receiving a valid uploaded file.
 *
 * It uses the model class and attribute name to retrieve the information
 * about the uploaded file. It then checks if a file is uploaded successfully,
 * if the file size is within the limit and if the file type is allowed.
 *
 * This validator will attempt to fetch uploaded data if attribute is not
 * previously set. Please note that this cannot be done if input is tabular:
 * <pre>
 *  foreach($models as $i=>$model)
 *     $model->attribute = CUploadedFile::getInstance($model, "[$i]attribute");
 * </pre>
 * Please note that you must use {@link CUploadedFile::getInstances} for multiple
 * file uploads.
 *
 * When using CFileValidator with an active record, the following code is often used:
 * <pre>
 *  if($model->save())
 *  {
 *     // single upload
 *     $model->attribute->saveAs($path);
 *     // multiple upload
 *     foreach($model->attribute as $file)
 *        $file->saveAs($path);
 *  }
 * </pre>
 *
 * You can use {@link CFileValidator} to validate the file attribute.
 *
 * In addition to the {@link message} property for setting a custom error message,
 * CFileValidator has a few custom error messages you can set that correspond to different
 * validation scenarios. When the file is too large, you may use the {@link tooLarge} property
 * to define a custom error message. Similarly for {@link tooSmall}, {@link wrongType} and
 * {@link tooMany}. The messages may contain additional placeholders that will be replaced
 * with the actual content. In addition to the "{attribute}" placeholder, recognized by all
 * validators (see {@link CValidator}), CFileValidator allows for the following placeholders
 * to be specified:
 * <ul>
 * <li>{file}: replaced with the name of the file.</li>
 * <li>{limit}: when using {@link tooLarge}, replaced with {@link maxSize};
 * when using {@link tooSmall}, replaced with {@link minSize}; and when using {@link tooMany}
 * replaced with {@link maxFiles}.</li>
 * <li>{extensions}: when using {@link wrongType}, it will be replaced with the allowed extensions.</li>
 * </ul>
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @version $Id$
 * @package system.validators
 * @since 1.0
 */
class FileValidatorAnnotation extends ValidatorAnnotation implements IBuiltInValidatorAnnotation
{

	/**
	 * @var boolean whether the attribute requires a file to be uploaded or not.
	 * Defaults to false, meaning a file is required to be uploaded.
	 */
	public $allowEmpty = false;

	/**
	 * @var mixed a list of file name extensions that are allowed to be uploaded.
	 * This can be either an array or a string consisting of file extension names
	 * separated by space or comma (e.g. "gif, jpg").
	 * Extension names are case-insensitive. Defaults to null, meaning all file name
	 * extensions are allowed.
	 */
	public $types = NULL;

	/**
	 * @var mixed a list of MIME-types of the file that are allowed to be uploaded.
	 * This can be either an array or a string consisting of MIME-types separated
	 * by space or comma (e.g. "image/gif, image/jpeg"). MIME-types are
	 * case-insensitive. Defaults to null, meaning all MIME-types are allowed.
	 * In order to use this property fileinfo PECL extension should be installed.
	 * @since 1.1.11
	 */
	public $mimeTypes = NULL;

	/**
	 * @var integer the minimum number of bytes required for the uploaded file.
	 * Defaults to null, meaning no limit.
	 * @see tooSmall
	 */
	public $minSize = NULL;

	/**
	 * @var integer the maximum number of bytes required for the uploaded file.
	 * Defaults to null, meaning no limit.
	 * Note, the size limit is also affected by 'upload_max_filesize' INI setting
	 * and the 'MAX_FILE_SIZE' hidden field value.
	 * @see tooLarge
	 */
	public $maxSize = NULL;

	/**
	 * @var string the error message used when the uploaded file is too large.
	 * @see maxSize
	 */
	public $tooLarge = NULL;

	/**
	 * @var string the error message used when the uploaded file is too small.
	 * @see minSize
	 */
	public $tooSmall = NULL;

	/**
	 * @var string the error message used when the uploaded file has an extension name
	 * that is not listed among {@link types}.
	 */
	public $wrongType = NULL;

	/**
	 * @var string the error message used when the uploaded file has a MIME-type
	 * that is not listed among {@link mimeTypes}. In order to use this property
	 * fileinfo PECL extension should be installed.
	 * @since 1.1.11
	 */
	public $wrongMimeType = NULL;

	/**
	 * @var integer the maximum file count the given attribute can hold.
	 * It defaults to 1, meaning single file upload. By defining a higher number,
	 * multiple uploads become possible.
	 */
	public $maxFiles = 1;

	/**
	 * @var string the error message used if the count of multiple uploads exceeds
	 * limit.
	 */
	public $tooMany = NULL;

}
