<?php

namespace Maslosoft\Addendum\Utilities;

use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Helpers\MiniView;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use SplFileInfo;

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
	 * This utility method find files containing $annotations
	 * annotates them and performs callback
	 * @param string[] $annotations
	 * @param callback $callback param is file path
	 */
	public static function fileWalker($annotations, $callback, $searchPaths = [])
	{
		$patterns = [];

		foreach ($annotations as $annotation)
		{
			$annotation = preg_replace('~^@~', '', $annotation);
			$patterns[] = sprintf('~@%s~', $annotation);
		}

		foreach ($searchPaths as $path)
		{
			$directoryIterator = new RecursiveDirectoryIterator($path);
			$iterator = new RecursiveIteratorIterator($directoryIterator);
			$regexIterator = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
			foreach ($regexIterator as $fileInfo)
			{
				/* @var $fileInfo SplFileInfo */
				$file = $fileInfo->getPathname();
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
