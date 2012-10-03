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
		 'annotations'
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
				// Default target names
				// NOTE: Netbeans uses different names than addendum.
				// This array is also used for renaming
				// Keys are addendum names, values are netbeans names
				$defaultTargets = [
					 'FUNCTION' => 'FUNCTION',
					 'CLASS' => 'TYPE',
					 'PROPERTY' => 'FIELD',
					 'METHOD' => 'METHOD'
				];
				$targets = [];
				if($info->hasAnnotation('Target'))
				{
					foreach($annotations as $annotation)
					{
						if($annotation instanceof TargetAnnotation)
						{
							$target = str_replace(array_keys($defaultTargets), array_values($defaultTargets), strtoupper($annotation->value));
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
				$matches = [];
				if(preg_match('~@template\s(.+)$~m', $comment, $matches))
				{
					$insertTemplate = sprintf('@%s', $matches[1]);
				}
				else
				{
					$insertTemplate = sprintf('@%s', $name);
				}
				$data = [
					 'insertTemplate' => $insertTemplate,
					 'name' => $name,
					 'targets' => $targets,
					 'description' => $comment,
					 'i' => $i++,
				];
				$result[] = $this->render('netbeansAnnotations', ['data' => (object)$data], true);
			}
		}
		// This is annotation for adding templates to annotations
		$data = [
					 'insertTemplate' => '@template ${template}',
					 'name' => 'template',
					 'targets' => ['TYPE'],
					 'description' => "Type in annotation for insert template, Do NOT use '@' sign here. \n@example Label('\${label}')",
					 'i' => $i++,
				];
			$result[] = $this->render('netbeansAnnotations', ['data' => (object)$data], true);
		file_put_contents(sprintf('%s/annotations.properties', $this->outputPath), implode("", $result));
	}

	/**
	 * Generate validator annotations from existing validator classes
	 */
	public function convertValidators()
	{
		$v = CValidator::$builtInValidators;
		$template = <<<CODE
<?php
/**
 * NOTE: This class is automatically generated from Yii validator class.
 * This is not actual validator. For validator class @see C%2\$s.
 */
%1\$s
class %2\$sAnnotation extends EValidatorAnnotation implements IBuiltInValidatorAnnotation
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
		foreach($v as $n => $class)
		{
			$name = ucfirst($n) . 'Validator';
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
			$code = sprintf($template, $classComment, $name, implode("\n", $fields));
			file_put_contents("c:/temp/{$name}Annotation.php", $code);
		}
	}
}