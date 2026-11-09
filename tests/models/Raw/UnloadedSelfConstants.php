<?php

namespace Maslosoft\AddendumTest\Models\Raw;

/**
 * @Label(self::class)
 */
class UnloadedSelfConstants
{
    const AllowedPattern = '\p{L}0-9-';
    const MatchPattern = '[' . self::AllowedPattern . ']+';
    const FullPattern = '^' . self::MatchPattern . '$';
    const ValidatorPattern = '~' . self::FullPattern . '~';

    /**
     * @Label(self::ValidatorPattern)
     * @Description(static::ValidatorPattern)
     * @var string
     */
    public $name = '';
}
