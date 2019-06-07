<?php


namespace AddendumTest\models\Cache\Writer;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

class ModelWithPartials implements AnnotatedInterface, InterfaceOne
{
	use TraitOne;
}