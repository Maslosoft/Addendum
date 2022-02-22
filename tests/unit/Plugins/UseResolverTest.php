<?php

namespace Plugins;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Interfaces\Matcher\IMatcher as WithAlias;
use Maslosoft\Addendum\Utilities\UseResolver;
use Maslosoft\AddendumTest\Models\Debug\Model2WithMethodInjection;
use ReflectionClass;
use ReflectionObject;
use UnitTester;
use function codecept_debug;

class UseResolverTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillResolveUseStatements()
	{
		$data = [
			'Test' => Test::class,
			'UseResolver' => UseResolver::class,
			'WithAlias' => WithAlias::class,
			'UnitTester' => UnitTester::class,
			'UseResolverStub' => UseResolverStub::class,
		];

		foreach ($data as $src => $expected)
		{
			$result = UseResolver::resolve(new ReflectionObject($this), $src);
			codecept_debug($src);
			$this->assertSame($expected, $result);
		}
	}

	public function testIfWillResolveClassNameWithFQN()
	{
		$data = [
			'Test' => Test::class,
			'UseResolver' => UseResolver::class,
			'WithAlias' => WithAlias::class,
			'UnitTester' => UnitTester::class,
			'UseResolverStub' => UseResolverStub::class,
		];
		$src = 'Maslosoft\SignalsTest\Signals\MethodInjected';
		$expected = 'Maslosoft\SignalsTest\Signals\MethodInjected';

		$result = UseResolver::resolve(new ReflectionClass(Model2WithMethodInjection::class), $src);
		$this->assertSame($expected, $result);
	}

}
