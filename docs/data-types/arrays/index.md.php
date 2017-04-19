<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>Arrays</title>

# Arrays

Arrays are defined slightly different than those in PHP, as curly braces are used,
keys are not quoted and are assigned by using `=` operator. Keys are optional,
if not specified zero-indexed integer keys will be used.

Any [type supported by addendum](../) can be array value, including array.

Example of passing array value to annotation:

```
@MyAnnotation({1, 2, 3})
```

Example of passing array value with keys:

```
@MyAnnotation({one = 1, two = 2, three = 3})
```

Example with class literal, and string

```
@MyAnnotation({MyClassLiteral, 'String value'})
```