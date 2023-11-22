<?php

namespace Debug;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\Debug\NestedTraits\ModelWithNestedTraits;
use UnitTester;

class NestedTraitAnnotationTest extends  \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillProperlyGetMetaFromNestedTrait()
	{
		$model = new ModelWithNestedTraits();

		$meta = Meta::create($model);

		$hasLabelMeta = $meta->hasLabel;

		$parentMeta = $meta->parentId;

		codecept_debug($hasLabelMeta);
		codecept_debug($parentMeta);

		$this->assertTrue(!empty($hasLabelMeta->label), 'That @Label is properly configured');

		$this->assertTrue(!empty($parentMeta->label), 'That @Label is detected on nested trait');
	}

}
