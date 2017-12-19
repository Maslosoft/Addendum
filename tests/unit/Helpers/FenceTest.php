<?php

namespace Helpers;

use Maslosoft\Addendum\Helpers\Fence;
use UnitTester;

class FenceTest extends \Codeception\TestCase\Test
{

	/**
	 * @var UnitTester
	 */
	protected $tester;

	protected function _before()
	{

	}

	protected function _after()
	{

	}

	// tests
	public function testIfWillRemoveFencedOutPartsOfAString()
	{
		$code = <<<CODE
Blahaa
```php
Some annotation
@X
```
Some other text
```php
Some other comment
@Y
```

  ```
				@Foo
				```

CODE;
		$this->assertNotFalse(strstr($code, '@'), 'That fenced out code has @\'s');
		Fence::out($code);
		$this->assertFalse(strstr($code, '@'), 'That fenced out code was removed');
	}

}
