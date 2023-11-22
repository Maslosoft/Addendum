<?php

namespace Target;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Exceptions\TargetException;
use Maslosoft\AddendumTest\Models\InterfaceTarget;
use Maslosoft\AddendumTest\Models\ModelWithBadTarget;
use Maslosoft\AddendumTest\Models\ModelWithBadTargetInterface;
use Maslosoft\AddendumTest\Models\ModelWithBadTargetInterfaceProperty;
use Maslosoft\AddendumTest\Models\ModelWithBadTargetMethod;
use Maslosoft\AddendumTest\Models\ModelWithBadTargetProperty;
use Maslosoft\AddendumTest\Models\ModelWithTarget;
use Maslosoft\AddendumTest\Models\ModelWithTargetInterface;
use Maslosoft\AddendumTest\Models\ModelWithTargetInterfaceProperty;
use Maslosoft\AddendumTest\Models\ModelWithTargetMethod;
use Maslosoft\AddendumTest\Models\ModelWithTargetProperty;
use UnitTester;

class TargetTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfHasTargetClass()
	{
		$model = new ModelWithTarget();
		$meta = Meta::create($model);

		$this->assertSame($meta->type()->target, 'class');
	}

	public function testIfHasTargetInterface()
	{
		$model = new ModelWithTargetInterface();
		$meta = Meta::create($model);

		$this->assertSame($meta->type()->target, InterfaceTarget::class);
	}

	public function testIfHasTargetInterfaceOnProperty()
	{
		$model = new ModelWithTargetInterfaceProperty();
		$meta = Meta::create($model);
		
		$this->assertSame($meta->title->target, InterfaceTarget::class);
	}

	public function testIfHasTargetProperty()
	{
		$model = new ModelWithTargetProperty();
		$meta = Meta::create($model);

		$this->assertSame($meta->test->target, 'property');
	}

	public function testIfHasTargetMethod()
	{
		$model = new ModelWithTargetMethod();
		$meta = Meta::create($model);

		$this->assertSame($meta->method('test')->target, 'method');
	}

	public function testIfHasWrongTargetClass()
	{
		$model = new ModelWithBadTarget();
		try
		{
			Meta::create($model);
			$this->assertTrue(false);
		}
		catch (TargetException $ex)
		{
			$this->assertTrue(true);
		}
	}

	public function testIfHasWrongTargetInterface()
	{
		$model = new ModelWithBadTargetInterface();
		try
		{
			Meta::create($model);
			$this->fail(sprintf('`%s` Should be thrown', TargetException::class));
		}
		catch (TargetException $ex)
		{
			$this->assertTrue(true);
		}
	}

	public function testIfHasWrongTargetInterfaceOnProperty()
	{
		$model = new ModelWithBadTargetInterfaceProperty();
		try
		{
			$meta = Meta::create($model);
			$this->assertTrue(false);
		}
		catch (TargetException $ex)
		{
			$this->assertTrue(true);
		}
	}

	public function testIfHasWrongTargetProperty()
	{
		$model = new ModelWithBadTargetProperty();
		try
		{
			Meta::create($model);
			$this->assertTrue(false);
		}
		catch (TargetException $ex)
		{
			$this->assertTrue(true);
		}
	}

	public function testIfHasWrongTargetMethod()
	{
		$model = new ModelWithBadTargetMethod();
		try
		{
			Meta::create($model);
			$this->assertTrue(false);
		}
		catch (TargetException $ex)
		{
			$this->assertTrue(true);
		}
	}

}
