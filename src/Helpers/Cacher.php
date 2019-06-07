<?php


namespace Maslosoft\Addendum\Helpers;


use Maslosoft\Addendum\Utilities\NameNormalizer;
use function str_replace;
use function trim;

class Cacher
{
	public static function classToFile($className): string
	{
		NameNormalizer::normalize($className);
		return trim(str_replace('\\', '.', $className), '.');
	}
}