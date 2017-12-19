<?php
/**
 * Created by PhpStorm.
 * User: peter
 * Date: 06.12.17
 * Time: 10:03
 */

namespace Maslosoft\AddendumTest\Models;


use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 *
 * Typical annotation with non-existent `PHPMD`
 * class in codeception.
 *
 * @SuppressWarnings(PHPMD)
 *
 * @package Maslosoft\AddendumTest\Models
 */
class ModelWithSilencedClassName implements AnnotatedInterface
{

}