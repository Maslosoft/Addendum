<?php

namespace Matcher;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\AddendumTest\Models\Arrays\ModelWithSquareBracketsSyntax;
use Maslosoft\AddendumTest\Models\ModelWithConstantValue;
use ReflectionClass;
use UnitTester;

class ArraySquareBracketTest extends Test
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

	public function testIfWillRawMatchArrayValue()
	{
		$doc = <<<'DOC'
	/**
	 * @ArrayValue([ModelWithConstantLabel, 'Plain String'])
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
		$this->assertSame(1, count($data['ArrayValue']));
		$this->assertSame(1, count($data['ArrayValue'][0]));

		$value = $data['ArrayValue'][0];

		$this->assertSame(ModelWithConstantLabel, $value['value'][0]);
		$this->assertSame('Plain String', $value['value'][1]);
	}

	public function testIfWillMatchArrayValue()
	{
		$model = new ModelWithSquareBracketsSyntax();
		$options = new MetaOptions();
		$options->namespaces[] = NamespacedAnnotation::Ns;
		$meta = Meta::create($model, $options)->type();

		$this->assertCount(3, $meta->values);
		$this->assertSame(1, $meta->values[0]);
		$this->assertSame(2, $meta->values[1]);
		$this->assertSame(3, $meta->values[2]);
	}

}
