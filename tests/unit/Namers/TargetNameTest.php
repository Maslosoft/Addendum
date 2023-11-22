<?php

namespace Namers;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\ReflectionName;
use Maslosoft\AddendumTest\Models\ModelWithFqnAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithTargetMethod;
use Maslosoft\AddendumTest\Models\ModelWithTargetProperty;
use UnitTester;

class TargetNameTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyNameClass()
	{
		$model = new ModelWithFqnAnnotation();
		$reflection = new ReflectionAnnotatedClass($model);
		$name = ReflectionName::createName($reflection);
		$this->assertSame(ModelWithFqnAnnotation::class, $name);
	}

	public function testIfWillProperlyNameProperty()
	{
		$model = new ModelWithTargetProperty();
		$reflection = new ReflectionAnnotatedProperty($model, 'test');
		$name = ReflectionName::createName($reflection);
		$this->assertSame(ModelWithTargetProperty::class . '@$test', $name);
	}

	public function testIfWillProperlyNameMethod()
	{
		$model = new ModelWithTargetMethod();
		$reflection = new ReflectionAnnotatedMethod($model, 'test');
		$name = ReflectionName::createName($reflection);
		$this->assertSame(ModelWithTargetMethod::class . '@test()', $name);
	}

}
