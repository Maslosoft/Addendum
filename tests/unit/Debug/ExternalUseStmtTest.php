<?php

namespace Debug;

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\CellEnumCss;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\Decorators\Published;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\PageItem;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\PageItemExtends;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\PageItemInline;
use Maslosoft\AddendumTest\Models\Debug\UseInTraits\PageItemOverride;
use UnitTester;

class ExternalUseStmtTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	public function testIfWillProperlyResolveUseStatmentsFromTraits()
	{
		$model = new PageItem();

		$this->check($model);
	}

	public function testIfWillProperlyResolveUseStatmentsFromInline()
	{
		$model = new PageItemInline();

		$this->check($model);
	}

	public function testIfWillProperlyResolveUseStatmentsFromAbstract()
	{
		$model = new PageItemExtends();

		$this->check($model);
	}

	public function testIfWillProperlyResolveUseStatmentsFromOverridenProperty()
	{
		$model = new PageItemOverride();

		$meta = $this->check($model);
		$this->assertSame('Override', $meta->label);
	}

	private function check($model)
	{
		$meta = Meta::create($model)->published;

		$this->assertNotFalse($meta);
		$this->assertNotNull($meta->decorators);
		$this->assertCount(2, $meta->decorators);
		$this->assertContains(Published::class, $meta->decorators);
		$this->assertContains(CellEnumCss::class, $meta->decorators);
		return $meta;
	}

}
