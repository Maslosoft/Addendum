<?php

namespace Maslosoft\AddendumTest\Models\Arrays;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * Class ModelWithSelfInArray
 *
 * @ArrayValue([self, self::One, self::Two, static::class])
 *
 * @package Maslosoft\AddendumTest\Models\Arrays
 */
class ModelWithSelfInArray implements AnnotatedInterface
{
	const One = 'one';
	const Two = 'two';
}