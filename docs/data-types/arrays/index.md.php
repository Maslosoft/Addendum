<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>4. Arrays</title>

# Arrays

Arrays might be defined with curly braces or square brackets.

Keys should be quoted quoted and are assigned by using `=` or `=>` operator.

Keys are optional, if not specified zero-indexed integer keys will be used.

Any [type supported by addendum](../) can be array value, including array.

<p class="alert alert-success">
    To assign array keys both simplified <code>=</code> symbol
    can be used or classic <code>=></code> notation.
</p>

### Square Brackets

##### With Classic `=>` Key Notation

Example of passing array value with keys using arrow notation and square brackets:

<p class="alert alert-success">
    The syntax you already know. With <code>[]</code> brackets and <code>=></code> operator.
</p>

```
@MyAnnotation(['one' => 1, 'two' => 2, 'three' => 3])
```

Array values can also written with square brackets, just
like in plain PHP.

Example of passing array value to annotation:

```
@MyAnnotation([1, 2, 3])
```

Example of passing array value with keys:

```
@MyAnnotation(['one' = 1, 'two' = 2, 'three' = 3])
```

Example with class literal, and string

```
@MyAnnotation([MyClassLiteral, 'String value'])
```

### Curly Braces

Example of passing array value to annotation:

```
@MyAnnotation({1, 2, 3})
```

Example of passing array value with keys:

```
@MyAnnotation({'one' = 1, 'two' = 2, 'three' = 3})
```

Example with class literal, and string

```
@MyAnnotation({MyClassLiteral, 'String value'})
```

##### With Classic `=>` Key Notation

Example of passing array value with keys using classic arrow operator:

```
@MyAnnotation({'one' => 1, 'two' => 2, 'three' => 3})
```

