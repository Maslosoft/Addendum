<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>Class Literals</title>

# Class Literals

Class literal is a special annotation value, which is present in PHP only
when using `instanceof` operator. As a name suggests, this is full class name
without quotes.

Main advantage of this data type, is that it considers `use` statements when
evaluating class name, so that short class name can be used.

Example of fully qualified class literal:

```
...
/**
* @MyAnnotation(MyVendor\MyProject\ClassName)
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
* @MyAnnotation(ClassName)
*/
public $name;
...
```