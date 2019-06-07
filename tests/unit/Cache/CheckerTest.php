<?php namespace Cache;

use AddendumTest\models\Cache\Writer\InterfaceBase;
use AddendumTest\models\Cache\Writer\InterfaceOne;
use AddendumTest\models\Cache\Writer\ModelWithPartials;
use AddendumTest\models\Cache\Writer\TraitBase;
use Codeception\Test\Unit;
use Maslosoft\Addendum\Cache\PhpCache\Checker;
use Maslosoft\Addendum\Cache\PhpCache\Writer;
use Maslosoft\Addendum\Helpers\Cacher;
use Maslosoft\Cli\Shared\Cmd;
use function sprintf;
use function time;
use function touch;
use UnitTester;

class CheckerTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

	/**
	 * @var Checker
	 */
    private $checker = null;
    
    protected function _before()
    {
		$path = sprintf('%s/runtime/*', __DIR__);
		Cmd::run("rm -rf $path");
		(new Writer(__DIR__ . '/runtime'))->write(ModelWithPartials::class, true);
		$this->checker = new Checker(__DIR__ . '/runtime');
    }

    protected function _after()
    {
    }

    // tests

	public function testCheckingClass()
	{
		$this->rewindClass(ModelWithPartials::class);

		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

    public function testCheckingBaseInterface()
    {
    	$this->rewindPartial(InterfaceBase::class);

    	$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
    }

	public function testCheckingDirectInterface()
	{
		$this->rewindPartial(InterfaceOne::class);

		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingBaseTrait()
	{
		$this->rewindPartial(TraitBase::class);

		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingTrait()
	{
		$this->rewindPartial(TraitBase::class);

		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	private function rewindPartial($partial, $by = 600)
	{
		$file = sprintf('%s/runtime/%s/%s.php', __DIR__, Cacher::classToFile(ModelWithPartials::class), Cacher::classToFile($partial));
		touch($file, time() - $by);
	}

	private function rewindClass($className, $by = 600)
	{
		$file = sprintf('%s/runtime/%s.php', __DIR__, Cacher::classToFile($className));
		touch($file, time() - $by);
	}
}