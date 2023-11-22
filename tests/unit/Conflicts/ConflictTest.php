<?php

namespace Conflicts;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Exceptions\ConflictException;
use Maslosoft\AddendumTest\Models\ModelWithConflictedAnnotation;
use UnitTester;

class ConflictTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function _before()
	{
		Addendum::cacheClear();
	}

	public function _after()
	{
		Addendum::cacheClear();
	}

	public function testConflictChecker()
	{
		// NOTE: Only for development, if ran this test, other tests will fail
//		try
//		{
//			ConflictChecker::check(new ConflictsWithLabelAnnotation(), new ReflectionAnnotatedClass(new ModelWithConflictedAnnotation));
//		}
//		catch (ConflictException $ex)
//		{
//			$this->assertTrue(true);
//		}
	}

//	public function testIfConflictAnnotationIsResolved()
//	{
//		$reflection = new ReflectionAnnotatedClass(new ConflictsWithLabelAnnotation());
//		$this->assertTrue($reflection->hasAnnotation('Conflicts'));
//	}

	public function testIfWillConflict()
	{
		$model = new ModelWithConflictedAnnotation();

		try
		{
			Meta::create($model);
			$this->assertTrue(false);
		}
		catch (ConflictException $ex)
		{
			$this->assertTrue(true);
		}
	}

	public function testIfWillConflictWithReversedAnnotationsOrder()
	{
		// NOTE: Only for development, if ran this test, other tests will fail
//		$model = new ModelWithConflictedAnnotation2();
//
//		try
//		{
//			Meta::create($model);
//			$this->assertTrue(false);
//		}
//		catch (ConflictException $ex)
//		{
//			$this->assertTrue(true);
//		}
	}

}
