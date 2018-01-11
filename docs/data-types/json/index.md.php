<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>4. JSON</title>

# JSON

JSON structures are supported both as a whole annotation value,
or as a parameter to other value

Any [type supported by addendum](../) can be JSON value, including array or JSON -
this means that complex JSON structures are supported as well.

<p class="alert alert-warning">
    While JSON structures are supported, resulting data will
    be associative array
</p>

### JSON Associative Array

With static values:

```
@MyAnnotation({'one': 1, 'two': 2, 'three': 3])
```

With class constants:

```
@MyAnnotation({'one': MyClass::Constant, 'two': MyClass::Constant2, 'three': MyClass::Constant3])
```

### JSON Array with Nested Square Brackets Array

```
@MyAnnotation({'one': 1, 'two': 2, 'three': [1, 2, 3]})
```

### Mixing with PHP array

It is possible to mix PHP array syntax and JSON syntax
altogether or to set JSON value to a named parameter.

<p class="alert alert-success">
    Having already prepared data in JSON format
    allows You to paste it directly in annotation value
</p>

This might be even more readable in some cases:

```
@MyAnnotation('config' = {'one': 1, 'two': 2})
```

This will result in `MyAnnotation` annotation having `value` of:

```
[
    'myValue' => [
        'one' => 1,
        'two' => 2
    ]
]
```

