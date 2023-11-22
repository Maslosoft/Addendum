<?php

namespace Debug;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\AddendumTest\Annotations\LabelAnnotation;
use Maslosoft\AddendumTest\Models\Debug\MultipleSameAnnotations\TestDerivedDocument;
use UnitTester;

class MultipleSameAnnotationTest extends  \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyGetMetaForMultipleSameAnnotationsOnDifferentClasses()
	{
		$model = new TestDerivedDocument();

		new ReflectionAnnotatedClass(LabelAnnotation::class);

		$info = new ReflectionAnnotatedClass($model);

		$annotations = $info->getAllAnnotations();

		$cb = function($data)
		{
			return $data->value;
		};

		$values = array_map($cb, $annotations);

		codecept_debug($values);

		$this->assertSame(2, count($annotations), 'That have two annotations');
	}

}
