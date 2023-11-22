<?php

namespace Inherit;

use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedMethod;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\AddendumTest\Models\Inherit\BaseTraitWithLabel;
use Maslosoft\AddendumTest\Models\Inherit\ModelWithInheritedLabelFromTrait;
use UnitTester;

class FromTraitTest extends  \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfHasAnnotationFromUsedTrait()
	{
		// Check trait first
		$info = new ReflectionAnnotatedClass(BaseTraitWithLabel::class);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelFromTrait();
		$info = new ReflectionAnnotatedClass($model);
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasPropertyAnnotationFromUsedTrait()
	{
		// Check trait first
		$info = new ReflectionAnnotatedProperty(BaseTraitWithLabel::class, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelFromTrait();
		$info = new ReflectionAnnotatedProperty($model, 'title');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

	public function testIfHasMethodAnnotationFromUsedTrait()
	{
		// Check trait first
		$info = new ReflectionAnnotatedMethod(BaseTraitWithLabel::class, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);

		// Check inherited model model
		$model = new ModelWithInheritedLabelFromTrait();
		$info = new ReflectionAnnotatedMethod($model, 'getTitle');
		$has = $info->hasAnnotation('Label');
		$this->assertTrue($has);
	}

}
