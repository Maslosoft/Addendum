<?php

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaType;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Zamm\ShortNamer;

;
?>
<?php
ShortNamer::defaults()->md;
$ai = new ShortNamer(AnnotatedInterface::class);
$m = new ShortNamer(Meta::class);
$mt = new ShortNamer(MetaType::class);
?>
<title>Registering Namespace</title>

# Registering Namespace

Annotation engine will search for annotation classes only on registered
namespaces. It uses [embedded dependency injection][embedi] to obtain
configuration. While this name might sound scary, without going into details,
attaching namespaces can be done with one method call.

<p class="alert alert-success">
    As for now PHP does not contain <code>::namespace</code> magic constant,
    so it is highly advisable to define class constant pointing
    to namespace with <code>const Ns = __NAMESPACE__</code>
</p>

### Attaching Configuration

```
use Maslosoft\Addendum\Addendum;
use Maslosoft\EmbeDi\Adapters\ArrayAdapter;
use Maslosoft\EmbeDi\EmbeDi;

$config = [
	'addendum' => [
		'class' => Addendum::class,
		'namespaces' => [
			'Acme\\Project\\Annotations'
		]
	]
];
EmbeDi::fly()->addAdapter(new ArrayAdapter($config));
```

The above snipped can be a bit more clean when used with one of annotations class
with predefined `Ns` constant:

```
use Maslosoft\Addendum\Addendum;
use Maslosoft\EmbeDi\Adapters\ArrayAdapter;
use Maslosoft\EmbeDi\EmbeDi;
use Acme\Project\Annotations\MyAnnotation;

$config = [
    'addendum' => [
        'class' => Addendum::class,
        'namespaces' => [
            MyAnnotation::Ns
        ]
    ]
];
EmbeDi::fly()->addAdapter(new ArrayAdapter($config));
```


See also [this repository][guide] and [blog post][post] for extra details.

[embedi]: /embedi/
[ca]: creating-annotations/
[guide]: https://github.com/MaslosoftGuides/addendum.first-annotation
[post]: https://maslosoft.com/blog/2017/03/27/creating-your-first-php-annotation-with-addendum/