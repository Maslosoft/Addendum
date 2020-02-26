<?php namespace Typing;

use Codeception\Test\Unit;
use Maslosoft\Addendum\Builder\DocComment;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Reflection\ReflectionFile;
use Maslosoft\AddendumTest\Models\Typing\ModelWithTypedProperties;
use ReflectionClass;
use UnitTester;
use function codecept_debug;
use function exec;

class TypedPropertiesTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    private $model;

    private $filename;

    protected function _before()
    {
    	exec('rm -rf /www/addendum/runtime/*');
    	if(PHP_VERSION_ID < 70400)
		{
			$this->markTestSkipped('This test is for PHP 7.4 or above');
			return;
		}
    	$this->model = new ModelWithTypedProperties();
		$r = new ReflectionClass($this->model);
//		codecept_debug((string)$r);
		$this->filename = $r->getFileName();
    }

    protected function _after()
    {
    }

    public function testPublicProperties()
	{
		$this->model = new ModelWithTypedProperties();
		$r = new ReflectionClass($this->model);
		$this->filename = $r->getFileName();

		$this->checkField('title', 'TEST', 'Title');
		$this->checkField('items', [1,2,3], 'Items');
		$this->checkField('items2', [1], 'Items2');
		$this->checkField('description', 'TEST2', 'Description');
		$this->checkField('user', null, 'User');
		$this->checkField('company', null, 'Company');

	}

	private function checkField($name, $default, $label)
	{
		$docExtractor = new DocComment();
		$docs = $docExtractor->forFile($this->filename);

		$this->assertArrayHasKey($name, $docs['fields']);

		$info = new ReflectionFile($this->filename);

		$propInfo = $info->hasProperty($name);
		$this->assertTrue($propInfo);

		$meta = Meta::create($this->model);

		$fieldMeta = $meta->field($name);

		$this->assertNotNull($fieldMeta);

		$this->assertSame($default, $fieldMeta->default);

		$this->assertSame($label, $fieldMeta->label, 'Annotation can be read');

		codecept_debug($fieldMeta->label);
	}
}