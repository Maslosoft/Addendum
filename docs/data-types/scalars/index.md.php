<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>Scalar Values</title>

# Scalar Values

Scalar values are simple PHP types, to define them in annotations,
write them like if it were in plain PHP.

Some examples will help to understand it

String values can be passed with single or double quotes:

```
@MyAnnotation('String value')
@MyAnnotation("String value")
```

Integers by passing number without floating point:

```
@MyAnnotation(123)
```

Boolean values by passing either `true` or `false`.

```
@MyAnnotation(true)
```

The same applies to `null` value:

```
@MyAnnotation(null)
```