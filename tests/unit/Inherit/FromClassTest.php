<?php

namespace Inherit;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\AddendumTest\Models\Inherit\BaseModelWithLabel;
use Maslosoft\AddendumTest\Models\Inherit\ModelWithInheritedLabel;
use Maslosoft\AddendumTest\Models\Inherit\ModelWithInheritedLabelDeep;
use UnitTester;

class FromClassTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfHasAnnotationFromParentClass()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabel();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasPropertyAnnotationFromParentClass()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedProperty($model, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabel();
		$info = new ReflectionAnnotatedProperty($model, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasMethodAnnotationFromParentClass()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabel();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	// tests
	public function testIfHasAnnotationFromParentClassParent()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelDeep();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasPropertyAnnotationFromParentClassParent()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedProperty($model, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelDeep();
		$info = new ReflectionAnnotatedProperty($model, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasMethodAnnotationFromParentClassParent()
	{
		// Check base model first
		$model = new BaseModelWithLabel();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelDeep();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

}
