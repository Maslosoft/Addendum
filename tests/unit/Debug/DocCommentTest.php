<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Debug;

use Codeception\Test\Unit as Test;
use Maslosoft\AddendumTest\Models\Debug\AbstractSettings;
use ReflectionClass;

/**
 * DocCommentTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class DocCommentTest extends Test
{

	public function testIfWillProperlyReadDocComment()
	{
		$class = new ReflectionClass(AbstractSettings::class);

		$doc = new \Maslosoft\Addendum\Builder\DocComment();

		$data = $doc->forClass($class);
		codecept_debug($data);
	}

}
