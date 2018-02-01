<?php

namespace Resolve;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\AddendumTest\Annotations\NamespacedShortAnnotation;
use Maslosoft\AddendumTest\Models\Arrays\ModelWithSelfInArray;
use Maslosoft\AddendumTest\Models\ModelWithNonNsAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithSelfKeyword;
use Maslosoft\AddendumTest\Models\ModelWithSelfKeywordConstant;
use Maslosoft\AddendumTest\Models\ModelWithShortAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithStaticClassKeyword;
use UnitTester;

class SelfTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillResolveSelfKeyword()
	{
		$model = new ModelWithSelfKeyword;
		$meta = Meta::create($model);

		$label = $meta->type()->label;
		$this->assertSame(ModelWithSelfKeyword::class, $label);
	}

	public function testIfWillResolveSelfKeywordConstant()
	{
		$model = new ModelWithSelfKeywordConstant;
		$meta = Meta::create($model);

		$label = $meta->type()->label;
		$this->assertSame(ModelWithSelfKeywordConstant::Label, $label);
	}

	public function testIfWillResolveStaticClassKeyword()
	{
		$model = new ModelWithStaticClassKeyword;
		$meta = Meta::create($model);

		$label = $meta->type()->label;
		$this->assertSame(ModelWithStaticClassKeyword::class, $label);
	}

	public function testIfWillResolveKeywordsSelfStaticAndStaticClassInArray()
	{
		$model = new ModelWithSelfInArray;
		$options = new MetaOptions();
		$options->namespaces[] = NamespacedAnnotation::Ns;
		$meta = Meta::create($model, $options)->type();

		$this->assertCount(4, $meta->values);
		$this->assertSame(ModelWithSelfInArray::class, $meta->values[0]);
		$this->assertSame(ModelWithSelfInArray::One, $meta->values[1]);
		$this->assertSame(ModelWithSelfInArray::Two, $meta->values[2]);
		$this->assertSame(ModelWithSelfInArray::class, $meta->values[3]);
	}
}
