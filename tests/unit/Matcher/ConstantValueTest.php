<?php

namespace Matcher;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithConstantValue;
use ReflectionClass;
use UnitTester;

class ConstantValueTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testRawMatcher()
	{
		$model = new ModelWithConstantValue();
		$reflection = new ReflectionAnnotatedClass($model);
		$matcher = new AnnotationsMatcher;
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));
		$matches = [];
		$matcher->matches(Addendum::getDocComment($reflection), $matches);
	}

	public function testIfWillRawMatchConstant()
	{
		$doc = <<<'DOC'
	/**
	 * @Label(ModelWithConstantLabel)
	 * @var string
	 */
DOC;
		$matcher = new AnnotationsMatcher;
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));
		$data = [];
		$matcher->matches($doc, $data);

		$this->assertSame(1, count($data));
		$this->assertSame(1, count($data['Label']));
		$this->assertSame(1, count($data['Label'][0]));

		$value = $data['Label'][0];

		$this->assertSame(ModelWithConstantLabel, $value['value']);
	}

	/**
	 * TODO Does not pass
	 */
	public function testIfWillMatchConstant()
	{
		$model = new ModelWithConstantValue();
		$options = new MetaOptions();
		$options->namespaces[] = NamespacedAnnotation::Ns;
		$meta = Meta::create($model, $options);

		// ModelWithConstantLabel defined in ModelWithConstantValue.php
		$this->assertTrue(defined('ModelWithConstantLabel'));
		$this->assertSame(ModelWithConstantLabel, $meta->title->label);
		$this->assertSame(ModelWithConstantValue::NameLabel, $meta->name->label);
	}

}
