<?php
if(!YII_DEBUG)
{
	throw new Exception(sprintf('Class %s should not be used on production environments', __CLASS__));
}
/**
 * This is utility class, should not be used in production environment
 *
 * @author Piotr
 */
class EAnnotationUtility
{

	/**
	 * Generate validator annotations from existing validator classes
	 */
	public static function convertValidators()
	{
		$v = [
			 'BooleanValidator',
			 'CaptchaValidator',
			 'CompareValidator',
			 'DateValidator',
			 'DefaultValueValidator',
			 'EmailValidator',
			 'ExistValidator',
			 'FileValidator',
			 'FilterValidator',
			 'InlineValidator',
			 'NumberValidator',
			 'RangeValidator',
			 'RegularExpressionValidator',
			 'RequiredValidator',
			 'SafeValidator',
			 'StringValidator',
			 'TypeValidator',
			 'UniqueValidator',
			 'UnsafeValidator',
			 'UrlValidator',
		];
		$template = <<<CODE
<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see C%2\$s.
 */
%1\$s
class %2\$sAnnotation extends EValidatorAnnotation
{
%3\$s
}
CODE;
		$info = new ReflectionClass('EValidatorAnnotation');
		foreach($info->getProperties(ReflectionProperty::IS_PUBLIC) as $field)
		{
			$ignored[$field->name] = $field->name;
		}
		$ignored['attributes'] = true;
		$ignored['builtInValidators'] = true;
		foreach($v as $n)
		{
			$class = "C$n";
			$info = new ReflectionAnnotatedClass($class);
			$classComment = $info->getDocComment();
			$values = $info->getDefaultProperties();
			$fields = [];
			foreach($info->getProperties(ReflectionProperty::IS_PUBLIC) as $field)
			{
				if(isset($ignored[$field->name]))
				{
					continue;
				}
				$comment = $field->getDocComment();
				$fields[$field->name] = sprintf("\t%s\n\tpublic \$%s = %s;\n", $comment, $field->name, var_export($values[$field->name], true));
			}
			$code = sprintf($template, $classComment, $n, implode("\n", $fields));
			file_put_contents("c:/temp/{$n}Annotation.php", $code);
		}
	}
}