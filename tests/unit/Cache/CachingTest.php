<?php

namespace Cache;

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Cache\NsCache;
use Maslosoft\AddendumTest\Models\Cache\CacheOptionsOne;
use Maslosoft\AddendumTest\Models\Cache\CacheOptionsTwo;
use Maslosoft\Cli\Shared\ConfigDetector;
use UnitTester;

class CachingTest extends \Codeception\Test\Unit
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

	// tests
	public function testIfWillProperlyCacheNamespacesForDifferentMetaContainers()
	{
		$path = (new ConfigDetector)->getRuntimePath();

		if (!is_dir($path))
		{
			mkdir($path);
		}

		// Simulate different meta container classes by creating different paths
		$path1 = sprintf('%s/path1', $path);
		$path2 = sprintf('%s/path2', $path);
		$path3 = sprintf('%s/path3', $path);

		if (!is_dir($path1))
		{
			mkdir($path1);
		}
		if (!is_dir($path2))
		{
			mkdir($path2);
		}
		if (!is_dir($path3))
		{
			mkdir($path3);
		}



		$ns1 = new NsCache($path1, Addendum::fly(), new CacheOptionsOne);

		$ns1->set();
		codecept_debug($ns1->get());
		$this->assertTrue($ns1->valid());

		$ns2 = new NsCache($path2, Addendum::fly(), new CacheOptionsTwo);
		$ns2->set();
		codecept_debug($ns2->get());

		$this->assertTrue($ns1->valid());
		$this->assertTrue($ns2->valid());

		$ns3 = new NsCache($path3, Addendum::fly(), new CacheOptionsOne);

		$ns3->set();
		codecept_debug($ns3->get());

		$this->assertTrue($ns1->valid());
		$this->assertTrue($ns2->valid());
		$this->assertTrue($ns3->valid());
	}

}
