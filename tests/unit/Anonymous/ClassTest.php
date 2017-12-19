<?php

namespace Anonymous;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\AddendumTest\Models\ModelWithAnonumousClass;
use UnitTester;

class ClassTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{
		if (version_compare(PHP_VERSION, '7.0.0', '<'))
		{
			throw new \PHPUnit_Framework_SkippedTestError('Feature available only on PHP 7');
		}
	}

	// tests
	public function testIfWillProperlyAnnotateAnonymousClass()
	{
		$model = null;
		$outer = new ModelWithAnonumousClass();
		$outer->get($model);
		$meta = Meta::create($model);

		$typeLabel = $meta->type()->label;
		$propertyLabel = $meta->field('test')->label;
		$trapLabel = $meta->trap;
		$methodLabel = $meta->method('doSomething')->label;


		$info = new ReflectionAnnotatedClass($model);

		codecept_debug($meta->type());
		codecept_debug($meta->test);
		codecept_debug($meta->method('doSomething'));


		codecept_debug($trapLabel);

		codecept_debug($info->getDocComment());
		codecept_debug($info->getProperty('test')->getDocComment());
		codecept_debug($info->getMethod('doSomething')->getDocComment());

		$this->assertFalse($trapLabel, 'That it did not include property from outer class');

		$this->assertSame('Class Name', $typeLabel, 'That type label was set');
		$this->assertSame('Test', $propertyLabel, 'That property label was set');
		$this->assertSame('Method Test', $methodLabel, 'That method label was set');
	}

}
