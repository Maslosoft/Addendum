<?php

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Zamm\Iterator;
use Maslosoft\Zamm\DocBlock;
?>
<title>2. Built-in annotations</title>

# Built-in annotations

Addendum contains several built-in annotation, which are meant to be used on
other annotations. These define constraints of annotations usage.

<?php

foreach (Iterator::ns(TargetAnnotation::class) as $class):
	echo '###' . str_replace('Annotation', '', (new ReflectionClass($class))->getShortName()) . PHP_EOL;
	echo new DocBlock($class);
endforeach;
?>