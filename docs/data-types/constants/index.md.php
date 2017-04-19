<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>2. Constants</title>

# Constants

Any constant value can be passed to annotation, just like if it were used in
PHP, by name without quotes:

```
@MyAnnotation(MY_GLOBAL_CONSTANT)
```

Also class constants can be used, also with use statements to benefit from
short class name.

Example of fully qualified class constant:

```
...
/**
* @MyAnnotation(MyVendor\MyProject\ClassName::MY_CONSTANT)
*/
public $name;
...
```

The same example with `use` statement:

```
...
use MyVendor\MyProject\ClassName;
...
/**
* @MyAnnotation(ClassName::MY_CONSTANT)
*/
public $name;
...
```

Addendum will also pre-process special constant prefixes of `self` and `static`,
to refer to class own constants:

```
...

const MY_CONSTANT = 1;

/**
* @MyAnnotation(self::MY_CONSTANT)
*/
public $name;
...
```