<?php

namespace Meta;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\AddendumTest\Models\ModelWithoutAnnotations;
use UnitTester;

class BaseInfoTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillReadBasicTypeMetaInformation()
	{
		$model = new ModelWithoutAnnotations();
		$meta = Meta::create($model);

		$type = $meta->type();
		$this->assertNotNull($type);
		$this->assertSame($type->name, ModelWithoutAnnotations::class);
	}

	public function testIfWillReadBasicPropertyMetaInformation()
	{
		$model = new ModelWithoutAnnotations();
		$meta = Meta::create($model);

		$property = $meta->title;
		$this->assertNotNull($property);
		$this->assertSame($property->name, 'title');
	}

	public function testIfWillReadBasicMethodMetaInformation()
	{
		$model = new ModelWithoutAnnotations();
		$meta = Meta::create($model);


		$this->assertNotNull($meta);

		$method = $meta->method('show');

		$this->assertTrue($method instanceof MetaMethod);

		$this->assertFalse($method->isAbstract);
		$this->assertFalse($method->isStatic);
		$this->assertSame($method->name, 'show');
	}

}
