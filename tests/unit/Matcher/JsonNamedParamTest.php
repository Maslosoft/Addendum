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

class JsonNamedParamTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillMatchJsonNamedParam()
	{
		$doc = <<<'DOC'
	/**
	 * @NamedValue(myValue = {'one': 1, 'two': 2})
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
		$this->assertSame(1, count($data['NamedValue']));
		$this->assertSame(1, count($data['NamedValue'][0]));

		$value = $data['NamedValue'][0];

		codecept_debug($value);

		$this->assertArrayHasKey('myValue', $value);
		$this->assertSame(1, $value['myValue']['one']);
		$this->assertSame(2, $value['myValue']['two']);
	}

	public function testIfWillMatchUnquotedJsonNamedParam()
	{
		$doc = <<<'DOC'
	/**
	 * @NamedValue(myValue = {one: 1, two: 2})
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
		$this->assertSame(1, count($data['NamedValue']));
		$this->assertSame(1, count($data['NamedValue'][0]));

		$value = $data['NamedValue'][0];

		codecept_debug($value);

		$this->assertArrayHasKey('myValue', $value);
		$this->assertSame(1, $value['myValue']['one']);
		$this->assertSame(2, $value['myValue']['two']);
	}
}
