<?php

namespace Matcher;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use Maslosoft\Addendum\Matcher\ParametersMatcher;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedProperty;
use Maslosoft\AddendumTest\Models\ModelWithTopValues;
use Maslosoft\AddendumTest\Models\ModelWithTopValuesDefaults;
use ReflectionClass;
use UnitTester;

class TopValuesTest extends Test
{

	public function testIfParamatersMatcherWillMatch()
	{
		$matcher = new ParametersMatcher();
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));
		$string = "('Maslosoft\\AddendumTest\\Models\\ModelWithLabels', true)";
		$value = [];
		$matcher->matches($string, $value);

		$this->assertSame('Maslosoft\AddendumTest\Models\ModelWithLabels', $value['value'][0]);
		$this->assertSame(true, $value['value'][1]);
	}

	public function testIfWillMatchTopValues()
	{
		$doc = <<<'DOC'
	/**
	 * @TopValues('Company\Emend\SomeClass', true)
	 * @var type
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
		$this->assertSame(1, count($data['TopValues']));
		$this->assertSame(1, count($data['TopValues'][0]));

		$value = $data['TopValues'][0];

		$this->assertSame('Company\Emend\SomeClass', $value['value'][0]);
		$this->assertSame(true, $value['value'][1]);
	}

	public function testIfWillExtractTopValues()
	{
		$model = new ModelWithTopValues;

		// Raw matcher for debug
		$reflection = new ReflectionAnnotatedProperty($model, 'straight');
		$matcher = new AnnotationsMatcher;
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => $reflection
		]));
		$data = [];
		$comment = Addendum::getDocComment($reflection);
		$matcher->matches($comment, $data);

		$meta = Meta::create($model);

		// All fields have same annotation
		foreach ($meta->fields() as $fieldMeta)
		{
			$title = sprintf('Annotation is defined on %s', $fieldMeta->name);
			$this->assertSame(ModelWithTopValues::ClassValue, $fieldMeta->class);
			$this->assertSame(ModelWithTopValues::UpdatableValue, $fieldMeta->updatable);
		}
	}

	public function testIfWillExtractTopValuesAndDefaultValuesSetOnAnnotatinClass()
	{
		$model = new ModelWithTopValuesDefaults;

		// Raw matcher for debug
		$reflection = new ReflectionAnnotatedProperty($model, 'straight');
		$matcher = new AnnotationsMatcher;
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => $reflection
		]));
		$data = [];
		$comment = Addendum::getDocComment($reflection);
		$matcher->matches($comment, $data);

		$meta = Meta::create($model);

		// All fields have same annotation
		foreach ($meta->fields() as $fieldMeta)
		{
			$title = sprintf('Annotation is defined on %s', $fieldMeta->name);
			$this->assertSame(ModelWithTopValuesDefaults::ClassValue, $fieldMeta->class);
			$this->assertSame(ModelWithTopValuesDefaults::UpdatableValue, $fieldMeta->updatable);
		}
	}
}
