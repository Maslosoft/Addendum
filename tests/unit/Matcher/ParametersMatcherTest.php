<?php

namespace Matcher;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Matcher\ParametersMatcher;
use Maslosoft\AddendumTest\Models\ModelWithLabels;
use Maslosoft\AddendumTest\Models\ModelWithTopValues;
use NonNamespaced;
use ReflectionClass;
use UnitTester;

class ParametersMatcherTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfParametrsWillMatch()
	{
		$matcher = new ParametersMatcher();
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));

		// To ensure use statement
		ModelWithLabels::class;
		$valueMatch = [
			// Simple types
			'(true)' => true,
			"('foo')" => 'foo',
			'("foo")' => 'foo',
			'(3)' => 3,
			'(-3)' => -3,
			'(2.3)' => 2.3,
			'(-2.3)' => -2.3,
			// Class literals
			'(NonNamespaced)' => 'NonNamespaced',
			'(Maslosoft\\AddendumTest\\Models\\ModelWithLabels)' => 'Maslosoft\\AddendumTest\\Models\\ModelWithLabels',
			'(ModelWithLabels)' => 'Maslosoft\\AddendumTest\\Models\\ModelWithLabels',
			// Static constants
			'(NonNamespaced::Test)' => NonNamespaced::Test,
			'(Maslosoft\\AddendumTest\\Models\\ModelWithTopValues::TestValue)' => ModelWithTopValues::TestValue,
			// Complex types
			'({1,2, 3})' => [1, 2, 3],
			'({1,-2, -3})' => [1, -2, -3],
			'({foo = 2, bar = 3})' => ['foo' => 2, 'bar' => 3],
			'({foo = 2, bar = -3})' => ['foo' => 2, 'bar' => -3],
			"({'foo' = 2, 'bar' = -3})" => ['foo' => 2, 'bar' => -3],
			"({'foo' => 2, 'bar' => -3})" => ['foo' => 2, 'bar' => -3],
			'({foo: 2, bar: -3})' => ['foo' => 2, 'bar' => -3],
			"({'foo': 2, 'bar': -3})" => ['foo' => 2, 'bar' => -3],
			"({'foo': 2, 'bar': [1,2,3]})" => ['foo' => 2, 'bar' => [1,2,3]],
			"([data = {'foo': 2, 'bar': -3}])" => ['data' => ['foo' => 2, 'bar' => -3]],
			"({'foo': 2, 'bar': {x: 'one', y: 'two', z: {'time': 1, 'gravity': 2}}})" => ['foo' => 2, 'bar' => ['x' => 'one', 'y' => 'two', 'z' => ['time' => 1, 'gravity' => 2]]],
			// Anonymous params
			'(Maslosoft\AddendumTest\Models\ModelWithLabels, 4, 5)' => [ModelWithLabels::class, 4, 5],
		];

		foreach ($valueMatch as $match => $value)
		{
			$msg = sprintf("Should match `%s` with value `%s`", $match, var_export($value, true));

			$matched = null;
			$this->assertTrue((bool) $matcher->matches($match, $matched), $msg);
			$this->assertSame($value, $matched['value'], $msg);
		}
	}

	public function testIfMixedDefinitionParametersWillMatch()
	{
		$matcher = new ParametersMatcher();
		$matcher->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));
		// Named values
		$namedMatch = [
			// Named params
			'(foo = 2, bar = 3)' => ['foo' => 2, 'bar' => 3],
			'(foo = 2, 3)' => ['foo' => 2, 0 => 3],
		];

		foreach ($namedMatch as $match => $value)
		{
			$msg = sprintf("Should match `%s` with named values of `%s`: `%s`", $match, implode('`, `', array_keys($value)), var_export($value, true));

			$matched = null;
			$this->assertTrue((bool) $matcher->matches($match, $matched), $msg);
			$this->assertSame($value, $matched['value'], $msg);
		}
	}

}
