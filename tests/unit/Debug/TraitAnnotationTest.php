<?php

namespace Debug;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Exceptions\ParseException;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\AddendumTest\Annotations\RelatedArrayAnnotation;
use Maslosoft\AddendumTest\Models\Debug\TreeTraits\ModelWithSimpleTree;
use Maslosoft\AddendumTest\Models\Debug\TreeTraits\Traits\SimpleTreeTrait;
use Maslosoft\AddendumTest\Models\Debug\TreeTraits\Traits\SimpleTreeTraitProper;
use UnitTester;

class TraitAnnotationTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyGetMetaFromTrait()
	{
		$model = new ModelWithSimpleTree();

		// Parse error in annotation
		try
		{
			$infoBad = new ReflectionAnnotatedProperty(SimpleTreeTrait::class, 'children');
			$relatedBad = $infoBad->getAnnotation('RelatedArray');
			$this->fail('Should throw exception');
		}
		catch (ParseException $e)
		{
			$this->assertTrue(true);
			codecept_debug('Exception was thrown properly');
		}
		$info = new ReflectionAnnotatedProperty(SimpleTreeTraitProper::class, 'children');
		$related = $info->getAnnotation('RelatedArray');

		$this->assertInstanceOf(RelatedArrayAnnotation::class, $related);

		$this->assertNotNull($related->value);

		try
		{
			$meta = Meta::create($model);
			$this->fail('Should throw exception');
		}
		catch (ParseException $e)
		{
			$this->assertTrue(true);
			codecept_debug('Exception was thrown properly');
		}
	}

}
