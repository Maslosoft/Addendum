<?php

namespace Raw;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\ModelWithUseStatements;
use ReflectionObject;
use UnitTester;

class ParserTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		DocComment::clearCache();
	}

	protected function _after()
	{

	}

	// tests
	public function testIfWillGetUseStatements()
	{
		$model = new ModelWithUseStatements();

		$doc = new DocComment();
		$info = new ReflectionObject($model);
		$fileName = $info->getFileName();
		$fileData = $doc->forFile($fileName);
		$classData = $doc->forClass($info);
		$expected = [
			Addendum::class,
			Annotation::class,
			Meta::class,
		];

		foreach($expected as $fqn)
		{
			$this->assertContains($fqn, $fileData['use']);
			$this->assertContains($fqn, $classData['use']);
		}
	}

}
