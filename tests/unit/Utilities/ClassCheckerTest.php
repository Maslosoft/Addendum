<?php
namespace Utilities;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Addendum\Utilities\ClassChecker;
use ReflectionClass;
use function codecept_debug;

class ClassCheckerTest extends \Codeception\Test\Unit
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
    public function testAnonymousClassDetection(): void
    {
		/**
		 * @Label('Class Name')
		 */
		$model = new class implements AnnotatedInterface {

			/**
			 * @Label('Test')
			 * @var type
			 */
			public $test = '';

			/**
			 * @Label('Method Test')
			 */
			public function doSomething(): void
			{

			}
		};

		$reflection = new ReflectionClass($model);

		codecept_debug($reflection->name);

		$isAnonymous = ClassChecker::isAnonymous($model);

		$this->assertTrue($isAnonymous, 'Class is anonymous');
    }
}