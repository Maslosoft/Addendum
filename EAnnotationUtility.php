<?php

if(!YII_DEBUG)
{
	throw new Exception(sprintf('Class %s should not be used on production environments', 'EAnnotationUtility'));
}

/**
 * This is utility class, should not be used in production environment
 *
 * @author Piotr
 */
class EAnnotationUtility extends CWidget
{
	public $searchPaths = [
		 'temp'
	];
	public $outputPath = 'c:/temp';

	/**
	 * Remove "*" from doc block
	 * @param string $comment
	 * @return string
	 */
	public function removeStars($comment)
	{
		$cleanComment = [
			 // Remove first line of doc block
			 '~/\*\*\s*$~m' => '',
			 // Remove last line
			 '~^\s*\*/\s*$~m' => '',
			 // Remove leading stars
			 '~^\s*\*~m' => '',
			 // Clean any leading whitespace
			 '~^\s*~' => ''
		];
		return preg_replace(array_keys($cleanComment), array_values($cleanComment), $comment);
	}

	/**
	 * Generate file for netbeans annotations code completition templates
	 * NOTE: Any error in file, after importing, makes netbeans annotations list empty and there is nothing indicating that fact
	 */
	public function generateNetbeansHelpers()
	{
		$result = [];
		$i = 0;
		foreach($this->searchPaths as $path)
		{
			$path = realpath(dirname(__FILE__) . '/' . $path);
			foreach(CFileHelper::findFiles($path, ['fileTypes' => ['php']]) as $file)
			{
				$className = preg_replace('~\.php$~', '', basename($file));
				$info = new ReflectionAnnotatedClass($className);
				if(!$info->isSubclassOf('EAnnotation'))
				{
					continue;
				}
				$annotations = $info->getAnnotations();
				$defaultTargets = ['FUNCTION', 'TYPE', 'FIELD', 'METHOD'];
				$targets = [];
				if($info->hasAnnotation('Target'))
				{
					foreach($annotations as $annotation)
					{
						if($annotation instanceof TargetAnnotation)
						{
							$target = str_replace('CLASS', 'TYPE', strtoupper($annotation->value));
							// Make sure that it has proper target, or annotations file will be broken
							if(in_array($target, $defaultTargets))
							{
								$targets[] = $target;
							}
						}
					}
				}
				else
				{
					$targets = $defaultTargets;
				}

				$comment = $this->removeStars($info->getDocComment());
				$name = preg_replace('~Annotation$~', '', $info->name);
				$data = [
					 'insertTemplate' => $name,
					 'name' => sprintf('@%s', $name),
					 'targets' => $targets,
					 'description' => $comment,
					 'i' => $i++,
				];
				$result[] = $this->render('netbeansAnnotations', ['data' => (object)$data], true);
			}
		}
		file_put_contents(sprintf('%s/annotations.properties', $this->outputPath), implode("", $result));
	}

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