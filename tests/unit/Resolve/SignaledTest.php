<?php

namespace Resolve;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\AddendumTest\Models\ModelWithSignaledAnnotation;
use Maslosoft\Signals\Signal;
use UnitTester;

class SignaledTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfSignaledAnnotationIsIncluded(): void
	{
		if(!ClassChecker::exists(Signal::class))
		{
			$this->markTestSkipped("maslosoft/signals not installed");
		}
		$model = new ModelWithSignaledAnnotation();

		$meta = Meta::create($model);
		$this->assertTrue($meta->type()->signaled);
	}

}
