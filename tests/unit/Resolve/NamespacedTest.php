<?php

namespace Resolve;

use Codeception\TestCase\Test;
use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Options\MetaOptions;
use Maslosoft\AddendumTest\Annotations\NamespacedShortAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithFqnAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithNonNsAnnotation;
use Maslosoft\AddendumTest\Models\ModelWithShortAnnotation;
use UnitTester;

class NamespacedTest extends Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	// tests
	public function testIfWillResolveFqnAnnotation()
	{
		$model = new ModelWithFqnAnnotation();
		$meta = Meta::create($model);

		$this->assertTrue($meta->type()->namespaced);
	}

	public function testIfWillResolveShortAnnotation()
	{
		$model = new ModelWithShortAnnotation();
		$options = new MetaOptions();
		$options->namespaces[] = NamespacedShortAnnotation::Ns;
		$meta = Meta::create($model, $options);

		$this->assertTrue($meta->type()->namespaced);
	}

	public function testIfWillResolveNonNamespacedAnnotation()
	{
		$model = new ModelWithNonNsAnnotation();
		$meta = Meta::create($model);
		$this->assertTrue($meta->type()->nonNamespaced);
	}

}
