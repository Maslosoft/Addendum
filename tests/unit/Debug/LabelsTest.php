<?php

namespace Debug;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\ModelWithLabels;
use UnitTester;

class LabelsTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillAnnotatateSameAnnotationOnMultipleFields()
	{
		$model = new ModelWithLabels();
		$meta = Meta::create($model);

		$this->assertSame(ModelWithLabels::TitleLabel, $meta->title->label);
		$this->assertSame(ModelWithLabels::StateLabel, $meta->state->label);
	}

}
