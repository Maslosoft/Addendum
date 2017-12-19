<?php

namespace Utilities;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Utilities\AnnotationUtility;
use UnitTester;

class AnnotationUtilityTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		
	}

	protected function _after()
	{

	}

	// tests
	public function testIfFileWalkerFilterOutFiles()
	{
		$annotations = [
			'Label'
		];

		$shouldMatch = [
			'ModelWithLabels.php' => true,
			'ModelWithConstantValue.php' => true,
			'ModelWithoutLabels.php' => false
		];
		$matched = [
			'ModelWithLabels.php' => false,
			'ModelWithConstantValue.php' => false,
			'ModelWithoutLabels.php' => false
		];
		$callback = function($path) use(&$matched)
		{
			$bn = basename($path);
			$matched[$bn] = true;
		};
		$callback->bindTo($this);

		$searchPaths = [
			MODELS_PATH
		];
		AnnotationUtility::fileWalker($annotations, $callback, $searchPaths);

		foreach ($shouldMatch as $file => $match)
		{
			$this->assertSame($match, $matched[$file]);
		}
	}

}
