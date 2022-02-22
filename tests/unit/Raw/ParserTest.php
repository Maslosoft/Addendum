<?php

namespace Raw;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Annotation;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\AddendumTest\Models\ModelWithSelfKeyword;
use Maslosoft\AddendumTest\Models\ModelWithUseStatements;
use ReflectionClass;
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

	public function testResolvingNamespace()
	{
		$reflection = new ReflectionClass(ModelWithSelfKeyword::class);
		$docs = (new DocComment())->forClass($reflection);

		$ns = $docs['namespace'];

		$this->assertNotEmpty($ns);
		$this->assertSame('Maslosoft\AddendumTest\Models', $ns);
	}

	public function testCollectingUseStatements()
	{
		$expectedUseStatements = [
			'Maslosoft\Addendum\Addendum',
			'Maslosoft\Addendum\Annotation',
			'Maslosoft\Addendum\Collections\Meta',
			'Maslosoft\Addendum\Collections\AddendumPlugins',
			'ReflectionClass',
		];
		$reflection = new ReflectionClass(ModelWithUseStatements::class);
		$docs = (new DocComment())->forClass($reflection);
		$useStatements = $docs['use'];
		$this->assertNotEmpty($useStatements);

		foreach ($expectedUseStatements as $key => $expectedStatement)
		{
			$currentStatement = $useStatements[$key];
			$this->assertNotEmpty($currentStatement);
			$this->assertSame($expectedStatement, $currentStatement);
		}
	}

	public function testParsingAnonymousClass()
	{
		/**
		 * @Label('Class Name')
		 */
		$model = new class implements AnnotatedInterface {

			/**
			 * @Label('Test')
			 * @var type
			 */
			public $test = '';

			/**
			 * @Label('Method Test')
			 */
			public function doSomething(): void
			{

			}
		};

		$reflection = new ReflectionClass($model);
		$docs = (new DocComment())->forClass($reflection);

		$this->assertNotEmpty($docs);
	}
}
