<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
use Maslosoft\Staple\Widgets\SubNavRecursive;
?>
<?php

/* @var $this MsWidget */
?>
<title>Data Types</title>

# Data Types

Annotation values are strongly typed, that is - they have explicit type,
depending on how it is defined. Except PHP scalar values, some expressions
are also evaluated.

#### Quick examples to see idea

```
@MyAnnotation('String value')
@MyAnnotation(123)
@MyAnnotation(1.23)
@MyAnnotation(MY_CONSTANT_VALUE)
@MyAnnotation(ClassName::MY_CLASS_CONSTANT_VALUE)
@MyAnnotation(Vendor\Project\ClassName)
```

### More on types:

<?php

echo new SubNavRecursive([
'root' => __DIR__,
 'path' => '.',
 'skipLevel' => 1,
]);
?>