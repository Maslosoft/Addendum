<?php namespace Raw;

use Maslosoft\Addendum\Reflection\ReflectionFile;
use Maslosoft\AddendumTest\Models\InterfaceTarget;
use Maslosoft\AddendumTest\Models\ModelWithLabels;
use Maslosoft\AddendumTest\Models\TraitWithLabels;
use ReflectionClass;
use function codecept_debug;

class TypesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testIfWillResolveTrait()
    {
    	$path = (new ReflectionClass(TraitWithLabels::class))->getFileName();
    	$info = new ReflectionFile($path);

    	codecept_debug($info->isTrait());
    	$this->assertTrue($info->isTrait());
    }

	public function testIfWillResolveNotTrait()
	{
		$path = (new ReflectionClass(ModelWithLabels::class))->getFileName();
		$info = new ReflectionFile($path);

		codecept_debug($info->isTrait());
		$this->assertFalse($info->isTrait());
	}

	public function testIfWillResolveInterface()
	{
		$path = (new ReflectionClass(InterfaceTarget::class))->getFileName();
		$info = new ReflectionFile($path);

		codecept_debug($info->isInterface());
		$this->assertTrue($info->isInterface());
	}

	public function testIfWillResolveNotInterface()
	{
		$path = (new ReflectionClass(ModelWithLabels::class))->getFileName();
		$info = new ReflectionFile($path);

		codecept_debug($info->isInterface());
		$this->assertFalse($info->isInterface());
	}
}