<?php

namespace Maslosoft\Addendum\Utilities;

use CFileHelper;
use CValidator;
use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Base\ValidatorAnnotation;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Helpers\MiniView;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use Yii;
use ZipArchive;

if (!YII_DEBUG)
{
	throw new \Exception(sprintf('Class %s should not be used on production environments', AnnotationUtility::class));
}

/**
 * This is utility class, should not be used in production environment
 * TODO Split this into separate classes
 * @codeCoverageIgnore
 * @author Piotr
 */
class AnnotationUtility
{

	public $searchPaths = [
		'annotations'
	];
	public $settingsPath = 'config/Preferences/org/netbeans/modules/php/project/';
	public $outputPath = null;

	/**
	 * View Renderer
	 * @var MiniView
	 */
	public $view = null;

	public function __construct()
	{
		$this->view = new MiniView($this);
	}

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
		$this->outputPath = Yii::getPathOfAlias('application.runtime');
		$result = [];
		$i = 0;
		foreach ($this->searchPaths as $path)
		{
			$path = realpath(dirname(__FILE__) . '/' . $path);
			foreach (CFileHelper::findFiles($path, ['fileTypes' => ['php']]) as $file)
			{
				$className = preg_replace('~\.php$~', '', basename($file));
				$info = new ReflectionAnnotatedClass($className);
				if (!$info->isSubclassOf('EAnnotation'))
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
				if ($info->hasAnnotation('Target'))
				{
					foreach ($annotations as $annotation)
					{
						if ($annotation instanceof TargetAnnotation)
						{
							$target = str_replace(array_keys($defaultTargets), array_values($defaultTargets), strtoupper($annotation->value));
							// Make sure that it has proper target, or annotations file will be broken
							if (in_array($target, $defaultTargets))
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
				if (preg_match('~@template\s(.+)$~m', $comment, $matches))
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
				$result[] = $this->view->render('netbeansAnnotations', ['data' => (object) $data], true);
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
		$result[] = $this->view->render('netbeansAnnotations', ['data' => (object) $data], true);

		// Pack it
		$fileName = 'annotations.properties';
		file_put_contents(sprintf('%s/%s', $this->outputPath, $fileName), implode("", $result));

		$zipName = 'annotations.zip';
		$zipPath = sprintf('%s/%s', $this->outputPath, $zipName);
		$zip = new ZipArchive;
		if (!is_writable($this->outputPath))
		{
			throw new RuntimeException(sprintf('Path %s is not wrtable', $this->outputPath));
		}
		if (true !== $zip->open($zipPath, ZipArchive::OVERWRITE))
		{
			throw new RuntimeException(sprintf('Cannot create zip archive %s', $zipPath));
		}
		if (!$zip->addFile(sprintf('%s/%s', $this->outputPath, $fileName), sprintf('%s/%s', $this->settingsPath, $fileName)))
		{
			throw new RuntimeException(sprintf('Cannot add file %s/%s to zip archive in %s', $zipPath, $zipName, $this->outputPath));
		}
		if (!$zip->close())
		{
			throw new RuntimeException(sprintf('Cannot close zip archive %s', $zipPath));
		}
		if (headers_sent())
		{
			throw new RuntimeException('Headers sent...');
		}
		ob_end_clean();
		header('Content-Type:application/zip');
		header("Content-Disposition: attachment; filename=$zipName");
		header("Content-Length: " . filesize($zipPath));
		echo file_get_contents($zipPath);
		Yii::app()->end();
	}

	/**
	 * This utility method find files containing $annotations
	 * annotates them and performs callback
	 * @param string[] $annotations
	 * @param callback $callback param is file path
	 */
	public static function fileWalker($annotations, $callback, $searchPaths = [])
	{
		Yii::app()->language = 'en';

		$patterns = [];

		foreach ($annotations as $annotation)
		{
			$annotation = preg_replace('~^@~', '', $annotation);
			$patterns[] = sprintf('~@%s~', $annotation);
		}
		if (!$searchPaths)
		{
			$searchPaths = Yii::getPathOfAlias('application');
		}
		foreach ($searchPaths as $path)
		{
			foreach (CFileHelper::findFiles($path, ['fileTypes' => ['php']]) as $file)
			{
				$parse = false;
				$contents = file_get_contents($file);
				foreach ($patterns as $pattern)
				{
					if ($parse)
					{
						continue;
					}
					if (preg_match($pattern, $contents))
					{
						$parse = true;
					}
				}
				if (!$parse)
				{
					continue;
				}
				call_user_func($callback, $file, $contents);
			}
		}
	}

	/**
	 * Annotate file <b>without</b> including php file and without using reflection.
	 * This method returns raw annotation values.
	 * <i>This is intented for various builders, which should not include files.</i>
	 * This <b>ALWAYS</b> parses file.
	 * @param string $file
	 * @param string $className <b>NOT RECOMMENDED!</b> Optional class name if multiple classes are declared in one file
	 * @return mixed[][]
	 */
	public static function rawAnnotate($file, $className = null)
	{
		Yii::import('addendum.builder.*');
		$docExtractor = new DocComment();
		$docs = $docExtractor->forFile($file, $className);

		$matcher = new AnnotationsMatcher();
		$class = [];
		$matcher->matches($docs['class'], $class);

		$methods = [];
		foreach ((array) $docs['methods'] as $name => $doc)
		{
			$methods[$name] = [];
			$matcher->matches($doc, $methods[$name]);
		}

		$fields = [];
		foreach ((array) $docs['fields'] as $name => $doc)
		{
			$fields[$name] = [];
			$matcher->matches($doc, $fields[$name]);
		}
		$result = [
			'namespace' => $docs['namespace'],
			'className' => $docs['className'],
			'class' => $class,
			'methods' => $methods,
			'fields' => $fields
		];
		return $result;
	}
}
