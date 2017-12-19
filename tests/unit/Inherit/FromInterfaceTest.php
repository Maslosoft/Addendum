<?php

namespace Inherit;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\AddendumTest\Models\Inherit\BaseInterfaceWithLabel;
use Maslosoft\AddendumTest\Models\Inherit\ModelWithInheritedLabelFromInterface;
use UnitTester;

class FromInterfaceTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfHasAnnotationFromImplementingInterface()
	{
		// Check interface first
		$info = new ReflectionAnnotatedClass(BaseInterfaceWithLabel::class);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelFromInterface();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasMethodAnnotationFromInterface()
	{
		// Check base model first
		$info = new ReflectionAnnotatedMethod(BaseInterfaceWithLabel::class, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelFromInterface();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

}
