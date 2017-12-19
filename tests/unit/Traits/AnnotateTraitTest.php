<?php

namespace Traits;

use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\AddendumTest\Models\TraitWithLabels;
use UnitTester;

class AnnotateTraitTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests

	public function testIfWillProperlyAnnotateTrait()
	{
		$info = new ReflectionAnnotatedClass(TraitWithLabels::class);
		$builder = new Builder();
		$annotations = $builder->build($info);

		$this->assertTrue($annotations->hasAnnotation('Label'));
	}

	public function testIfWillProperlyAnnotateTraitProperty()
	{
		$info = new ReflectionAnnotatedProperty(TraitWithLabels::class, 'title');
		$builder = new Builder();
		$annotations = $builder->build($info);

		$this->assertTrue($annotations->hasAnnotation('Label'));
	}

}
