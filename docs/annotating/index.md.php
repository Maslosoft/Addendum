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
<title>0. Annotating Classes</title>

# Annotating Classes

The annotation are special kinds of PHPDoc documentation parts,
which can be interpreted later to obtain extra information
about class, it's methods and properties.

To annotate class add special documentation value. This value
can also take arguments based in particular annotation
implementation. As each of `@AnnotationName` blocks
will load corresponding class and set it's parameters.

<p class="alert alert-success">
    Each class with annotations <i>must</i> implement
    <?php
	ShortNamer::defaults()->html;
    ?>
    <?= $ai; ?>. This interface is empty, it is
	<?php
	ShortNamer::defaults()->md;
	?>
    information that the class should be parsed by annotation engine.
</p>

### Example of annotating class

```
namespace Acme\Components;

use Maslosoft\Addendum\Interfaces\AnnotatedInterface;

/**
 * @Label('My Component')
 */
class MyComponent implements AnnotatedInterface
{
}

```

Having `@Label` placed on `MyComponent` will allow annotations
engine to [create instance][ca] of `LabelAnnotation` and set
it's value to `Component`.

#### Obtaining data

To obtain [pre-processed value][ca] of annotation the preferred
way is to use <?= $m; ?> container, which will return object containing
metadata.

<p class="alert alert-success">
    Meta container is designed to be very lightweight. Once
    data is cached, it will perform like normal PHP code.
</p>

In this example, we assume that `@Label` will simply set `label`
property of <?= $m; ?>:

```
$meta = Meta::create(MyComponent::class)
```

The value of `$meta` is instance of <?= $m; ?>; To get data
for *class*, we need to call <?= $m->type(); ?> method which
will return instance of - yes You guessed it - <?= $mt; ?>.

This object contains our `label` value:

```
echo $meta->type()->label; //My Component
```

Will output *My Component*.

See also [this repository][guide] and [blog post][post] for extra details.

[ca]: creating-annotations/
[guide]: https://github.com/MaslosoftGuides/addendum.first-annotation
[post]: https://maslosoft.com/blog/2017/03/27/creating-your-first-php-annotation-with-addendum/