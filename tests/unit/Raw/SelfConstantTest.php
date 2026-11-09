<?php

namespace Raw;

use Codeception\Test\Unit as Test;
use Maslosoft\Addendum\Utilities\AnnotationUtility;
use Maslosoft\AddendumTest\Models\Raw\UnloadedSelfConstants;

/**
 * Reproduces issue #48 without loading or executing the inspected class.
 */
class SelfConstantTest extends Test
{
    public function testRawSelfAndStaticConstantsFromUnloadedClass()
    {
        $path = MODELS_PATH . '/Raw/UnloadedSelfConstants.php';
        $className = UnloadedSelfConstants::class;
        $this->assertFalse(class_exists($className, false));

        $result = AnnotationUtility::rawAnnotate($path);

        $this->assertSame($className, $result['class']['Label'][0]['value']);
        $expected = '~^[\p{L}0-9-]+$~';
        $this->assertSame($expected, $result['fields']['name']['Label'][0]['value']);
        $this->assertSame($expected, $result['fields']['name']['Description'][0]['value']);
        $this->assertFalse(class_exists($className, false), 'Raw annotation parsing must not load the class');
    }
}
