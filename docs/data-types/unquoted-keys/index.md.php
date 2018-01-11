<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
?>
<?php
/* @var $this MsWidget */
?>
<title>9. Unquoted Keys</title>

# Unquoted Keys

While this is not really data type, it is kind of convention
allowing to use unquoted keys in complex structures.

<p class="alert alert-danger">
    If constant exists named same as key, the constant value
    <b>will be used instead</b> also be aware of <code>self</code>
    or <code>static</code> keywords.
</p>

Having above warning in mind, it is better to quote keys
with single `'` or double `"` quote . However most likely keys will
be lowercase and constant uppercase, it is up to
developer to use unquoted keys.

### Example with JSON data

```
@SomeValue({one: 1, two: 2})
```

Will result in annotation value:

```
[
    'one' => 1,
    'two' => 2
]
```

### Example with named parameter

```
@NamedValue(myValue = {one: 1, two: 2})
```


Will result in annotation value:

```
[
    'myValue' => [
        'one' => 1,
        'two' => 2
    ]
]
```