<?php

namespace Resolve;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\ModelWithSignaledAnnotation;
use UnitTester;

class SignaledTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfSignaledAnnotationIsIncluded()
	{
		$model = new ModelWithSignaledAnnotation();

		$meta = Meta::create($model);
		$this->assertTrue($meta->type()->signaled);
	}

}
