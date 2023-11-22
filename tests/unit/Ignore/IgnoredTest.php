<?php

namespace Ignore;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\Addendum\Utilities\IgnoredChecker;
use Maslosoft\AddendumTest\Models\Ignored\ModelWithIgnoredComplex;
use Maslosoft\AddendumTest\Models\ModelWithIgnoredEntities;
use Maslosoft\AddendumTest\Models\ModelWithIgnoredTrait;
use UnitTester;

class IgnoredTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfPropertyAndMethodAreIgnored()
	{
		$model = new ModelWithIgnoredEntities();
		$this->runChecksFor($model);
	}

	// tests
	public function testIfPropertyAndMethodAreIgnoredWhenTheyAreDefinedInTrait()
	{
		$model = new ModelWithIgnoredTrait();
		$this->runChecksFor($model);
	}

	public function testIfPropertyAndMethodAreIgnoredWhenThereAreSeveralPartials()
	{
		$model = new ModelWithIgnoredComplex();
		$this->runChecksFor($model);
	}

	private function runChecksFor($model)
	{
		codecept_debug(get_class($model));
		$meta = Meta::create($model);

		// Default behavior
		$this->assertInstanceOf(MetaProperty::class, $meta->title);
		$this->assertInstanceOf(MetaMethod::class, $meta->method('getTitle'));

		$info = new ReflectionAnnotatedProperty($model, 'meta');

		$result = IgnoredChecker::check($info);

		$this->assertTrue($result);

		// Must not have metadata
		$this->assertFalse($meta->meta);
		$this->assertFalse($meta->method('getMeta'));

		// Must have metadata
		$this->assertInstanceOf(MetaProperty::class, $meta->must);
		$this->assertInstanceOf(MetaMethod::class, $meta->method('getMust'));
	}

}
