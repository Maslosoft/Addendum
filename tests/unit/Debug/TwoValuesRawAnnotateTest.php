<?php

namespace Debug;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\MatcherConfig;
use Maslosoft\Addendum\Matcher\AnnotationsMatcher;
use ReflectionClass;
use UnitTester;

class TwoValuesRawAnnotateTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

	protected function _after()
	{

	}

	/**
	 * Was not matching class literal with numbers, see maslosoft/addendum@ac22a08e01601208abae2bfd9b22c61adc461265
	 */
	public function testIfMatcherWillMatchClassLiteralWithNumbers()
	{
		$doc = <<<'DOC'
	/**
	 * @SlotFor(Maslosoft\AddendumTest\Models\Debug\Signals\ClassWithNumber6)
	 * @SlotFor(Maslosoft\AddendumTest\Models\Debug\Signals\ClassWith3Number)
	 * @param ISignal $signal
	 */
DOC;

		$parser = new AnnotationsMatcher;
		$parser->setPlugins(new MatcherConfig([
			'addendum' => new Addendum(),
			'reflection' => new ReflectionClass($this)
		]));
		$data = [];
		$parser->matches($doc, $data);

		$this->assertSame(1, count($data));
		$this->assertSame(2, count($data['SlotFor']));
		$this->assertSame(1, count($data['SlotFor'][0]));
		$this->assertSame(1, count($data['SlotFor'][1]));
	}

}
