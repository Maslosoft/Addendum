<?php
namespace Plugins;


use Maslosoft\Addendum\Builder\Builder;
use Maslosoft\Addendum\Exceptions\ClassNotFoundException;
use Maslosoft\Addendum\Reflection\ReflectionAnnotatedClass;
use Maslosoft\AddendumTest\Models\ModelWithSilencedClassName;

class ClassErrorSilencerTest extends \Codeception\Test\Unit
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
    public function testDetectionOfIgnoredClass()
    {
    	$model = new ModelWithSilencedClassName;
    	$builder = new Builder();

    	try
		{
			$reflection = new ReflectionAnnotatedClass($model);
		}
		catch (ClassNotFoundException $e)
		{
			codecept_debug($e->getMessage());
			$this->assertFalse(true, 'Class was not ignored');
		}
    }
}