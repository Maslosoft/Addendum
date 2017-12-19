<?php

namespace Meta;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\AddendumTest\Models\ModelWithoutAnnotations;
use UnitTester;

class BaseInfoFromClassNameTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillReadBasicTypeMetaInformation()
	{
		$meta = Meta::create(ModelWithoutAnnotations::class);

		$type = $meta->type();
		$this->assertNotNull($type);
		$this->assertSame($type->name, ModelWithoutAnnotations::class);
	}

	public function testIfWillReadBasicPropertyMetaInformation()
	{
		$meta = Meta::create(ModelWithoutAnnotations::class);

		$property = $meta->title;
		$this->assertNotNull($property);
		$this->assertSame($property->name, 'title');
	}

	public function testIfWillReadBasicMethodMetaInformation()
	{
		$meta = Meta::create(ModelWithoutAnnotations::class);


		$this->assertNotNull($meta);

		$method = $meta->method('show');

		$this->assertTrue($method instanceof MetaMethod);

		$this->assertFalse($method->isAbstract);
		$this->assertFalse($method->isStatic);
		$this->assertSame($method->name, 'show');
	}

}
