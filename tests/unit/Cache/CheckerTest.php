<?php namespace Cache;

use AddendumTest\models\Cache\Writer\InterfaceBase;
use AddendumTest\models\Cache\Writer\InterfaceOne;
use AddendumTest\models\Cache\Writer\ModelWithPartials;
use AddendumTest\models\Cache\Writer\TraitBase;
use function clearstatcache;
use function codecept_debug;
use Codeception\Test\Unit;
use Maslosoft\Addendum\Cache\PhpCache\Checker;
use Maslosoft\Addendum\Cache\PhpCache\Writer;
use Maslosoft\Cli\Shared\Cmd;
use ReflectionClass;
use function realpath;
use function sleep;
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
    private Checker $checker;

	/**
	 * @var string
	 */
	private string $rootDir;

	protected function _before(): void
    {
		$this->rootDir = realpath(__DIR__ . '/../../../');
		$this->clear();
		(new Writer($this->rootDir . '/runtime'))->write(ModelWithPartials::class, true);
		$this->checker = new Checker($this->rootDir . '/runtime');
    }

    protected function _after(): void
    {
    }

    // tests
	public function testCheckingNotModified(): void
	{
		$this->assertTrue($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingNotCached(): void
	{
		$this->clear();
		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingClass(): void
	{
		$this->touch(ModelWithPartials::class);
		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

    public function testCheckingBaseInterface(): void
    {
    	$this->touch(InterfaceBase::class);
    	$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
    }

	public function testCheckingDirectInterface(): void
	{
		$this->touch(InterfaceOne::class);
		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingBaseTrait(): void
	{
		$this->touch(TraitBase::class);
		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	public function testCheckingTrait(): void
	{
		$this->touch(TraitBase::class);
		$this->assertFalse($this->checker->isValid(ModelWithPartials::class));
	}

	private function touch($partial): void
	{
		$file = (new ReflectionClass($partial))->getFileName();
		$this->assertFileExists($file, 'File to clear cache does not exits');
		codecept_debug($file);
		clearstatcache(true, $file);
		touch($file, time() + 1);
	}

	private function clear(): void
	{
		$path = sprintf('%s/runtime/*', $this->rootDir);
		Cmd::run("rm -rf $path");
	}
}