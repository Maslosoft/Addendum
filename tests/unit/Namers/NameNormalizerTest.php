<?php

namespace Namers;

use Maslosoft\Addendum\Utilities\NameNormalizer;
use UnitTester;

class NameNormalizerTest extends  \Codeception\Test\Unit
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testNameNormalizer()
	{
		$test = [
			'\\' => '\\',
			'Maslosoft\\Addendum\\Annotations' => 'Maslosoft\\Addendum\\Annotations',
			'Maslosoft\\AddendumTest\\Annotations' => 'Maslosoft\\AddendumTest\\Annotations',
			'\\Maslosoft\\AddendumTest\\Annotations\\SignaledNs' => 'Maslosoft\\AddendumTest\\Annotations\\SignaledNs',
		];
		foreach ($test as $actual => $expected)
		{
			NameNormalizer::normalize($actual, false);
			$this->assertSame($actual, $expected);
		}
	}

	public function testNameNormalizerWithTrailingSlash()
	{
		$test = [
			'\\' => '\\',
			'Maslosoft\\Addendum\\Annotations' => '\\Maslosoft\\Addendum\\Annotations',
			'Maslosoft\\AddendumTest\\Annotations' => '\\Maslosoft\\AddendumTest\\Annotations',
			'\\Maslosoft\\AddendumTest\\Annotations\\SignaledNs' => '\\Maslosoft\\AddendumTest\\Annotations\\SignaledNs',
		];
		foreach ($test as $actual => $expected)
		{
			NameNormalizer::normalize($actual, true);
			$this->assertSame($actual, $expected);
		}
	}

}
