<?php

namespace Resolve;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\AddendumTest\Annotations\AnotherNs\SecondNsAnnotation;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\AddendumTest\Annotations\ThirdNs\ThirdNsAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithTwoNsAnnotations;
use UnitTester;

class AddedNamespaceTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillRecognizeAnnotationAfterDynamicallyAddingNamespace()
	{
		$this->markTestIncomplete("This is broken and possibly will not be implemented");
		$model = new ModelWithTwoNsAnnotations();

		$options = new MetaOptions();
		$options->namespaces[] = NamespacedAnnotation::Ns;

		$meta = Meta::create($model, $options)->title;

		$this->assertTrue($meta->namespaced);

		// Not added second namespace
		$this->assertNull($meta->second);
		$this->assertNull($meta->third);

		// Second ns
		$options->namespaces[] = SecondNsAnnotation::Ns;

		$metaContainer2 = Meta::create($model, $options);

		$this->assertNotNull($metaContainer2);

		$meta2 = $metaContainer2->title;

		$this->assertTrue($meta2->namespaced);

		// Added second namespace
		$this->assertTrue($meta2->second, 'That namespace added via options id detected');

		// Third ns
		Addendum::fly()->addNamespace(ThirdNsAnnotation::Ns);

		$meta3 = Meta::create($model, $options)->title;

		$this->assertTrue($meta3->namespaced);
		$this->assertTrue($meta3->second);

		// Added second namespace
		$this->assertTrue($meta3->third, 'That namespace added via Addendum::addNamespace is detected');
	}

}
