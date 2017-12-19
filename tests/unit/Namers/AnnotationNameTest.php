<?php

namespace Namers;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Utilities\AnnotationName;
use Maslosoft\AddendumTest\Annotations\LabelAnnotation;
use UnitTester;

class AnnotationNameTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

	protected function _after()
	{
		
	}

	// tests
	public function testIfWillProperlyNameAnnotation()
	{
		$annotation = new LabelAnnotation();

		$this->assertSame('Label', AnnotationName::createName($annotation));
	}

}
