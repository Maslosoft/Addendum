<?php namespace Cache;

use AddendumTest\models\Cache\Writer\InterfaceBase;
use AddendumTest\models\Cache\Writer\InterfaceOne;
use AddendumTest\models\Cache\Writer\ModelWithPartials;
use AddendumTest\models\Cache\Writer\TraitBase;
use AddendumTest\models\Cache\Writer\TraitOne;
use Codeception\Test\Unit;
use Maslosoft\Addendum\Cache\PhpCache\Writer;
use Maslosoft\Addendum\Helpers\Cacher;
use Maslosoft\Cli\Shared\Cmd;
use Maslosoft\Cli\Shared\Os;
use function sprintf;
use UnitTester;

class WriterTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    	$path = sprintf('%s/runtime/*', __DIR__);
    	Cmd::run("rm -rf $path");
    }

    protected function _after()
    {
    }

    // tests
    public function testWriter()
    {
    	$writer = new Writer(__DIR__ . '/runtime');

    	$writer->write(ModelWithPartials::class, true);

    	$this->checkClass(ModelWithPartials::class);
		$this->checkPartial(InterfaceOne::class);
		$this->checkPartial(InterfaceBase::class);
		$this->checkPartial(TraitOne::class);
		$this->checkPartial(TraitBase::class);
	}

    private function checkPartial($partial)
	{
		$file = sprintf('%s/runtime/%s/%s.php', __DIR__, Cacher::classToFile(ModelWithPartials::class), Cacher::classToFile($partial));
		$this->assertFileExists($file);
	}

	private function checkClass($className)
	{
		$file = sprintf('%s/runtime/%s.php', __DIR__, Cacher::classToFile($className));
		$this->assertFileExists($file);
	}
}