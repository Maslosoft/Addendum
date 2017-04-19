<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>Params</title>

# Params

Params are very similar to [arrays](../arrays) except that curly braces are omitted.
Might be defined with unquoted keys and are assigned by using `=` operator. Keys are optional,
if not specified zero-indexed integer keys will be used.

Any [type supported by addendum](../) can be array value, including array. But
params cannot be used as a sub type - this type is only available as a top
value.

**It's purpose is just to make multi-param annotations more readable.**

Example of passing params to annotation:

```
@MyAnnotation(1, 2, 3)
```

Example of passing params with keys:

```
@MyAnnotation(one = 1, two = 2, three = 3)
```

Example with class literal, and string

```
@MyAnnotation(MyClassLiteral, 'String value')
```