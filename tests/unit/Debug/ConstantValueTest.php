<?php

namespace Debug;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\Debug\ModelWithRequiredValueValidator;
use Maslosoft\AddendumTest\Models\ModelWithLabels;
use UnitTester;

class ConstantValueTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillResolveSelfConstantValue()
	{
		$model = new ModelWithRequiredValueValidator();
		$meta = Meta::create($model);

		$this->assertSame(ModelWithRequiredValueValidator::RequiredValue, $meta->login->requiredValue);
	}

	public function testIfWillResolveSelfConstantValueUsingSelfKeyword()
	{
		$model = new ModelWithRequiredValueValidator();
		$meta = Meta::create($model);

		$this->assertSame(ModelWithRequiredValueValidator::RequiredValue, $meta->loginSelf->requiredValue);
	}

	public function testIfWillResolveSelfConstantValueUsingStaticKeyword()
	{
		$model = new ModelWithRequiredValueValidator();
		$meta = Meta::create($model);

		$this->assertSame(ModelWithRequiredValueValidator::RequiredValue, $meta->loginStatic->requiredValue);
	}

}
