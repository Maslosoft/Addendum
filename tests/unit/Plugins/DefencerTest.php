<?php

namespace Plugins;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Utilities\AnnotationUtility;
use Maslosoft\AddendumTest\Models\ModelWithFences;
use ReflectionObject;
use UnitTester;

class DefencerTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIgnoreAnnotationsSurroundedByFence()
	{
		$model = new ModelWithFences();
		$meta = Meta::create($model)->type();

		codecept_debug($meta);

		$this->assertSame('LABEL', $meta->label, 'That @Label was evaluated');
		$this->assertTrue(empty($meta->description), 'That @Description was ignoded');
	}

	public function testIgnoreAnnotationsSurroundedByFenceWhenRawAnnotateIsUsed()
	{
		$model = new ModelWithFences();
		$file = (new ReflectionObject($model))->getFileName();
		$annotations = AnnotationUtility::rawAnnotate($file);

		$this->assertTrue(!empty($annotations['class']), 'That has class annotations are present');

		codecept_debug($annotations['class']);


		$this->assertTrue(!empty($annotations['class']['Label']), 'That has @Label annotation is present');

		$this->assertTrue(!empty($annotations['class']['Label'][0]['value']), 'That has @Label has value');

		$this->assertSame('LABEL', $annotations['class']['Label'][0]['value'], 'That @Label has proper value');

		$this->assertFalse(array_key_exists('Description', $annotations['class']), 'That @Description was ignored');
	}

}
