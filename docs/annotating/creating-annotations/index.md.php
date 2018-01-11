<?php

use Maslosoft\Addendum\Collections\Meta;
use Maslosoft\Addendum\Collections\MetaAnnotation;
use Maslosoft\Addendum\Collections\MetaMethod;
use Maslosoft\Addendum\Collections\MetaProperty;
use Maslosoft\Addendum\Collections\MetaType;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Addendum\Interfaces\AnnotatedInterface;
use Maslosoft\Zamm\ShortNamer;

;
?>
<?php
ShortNamer::defaults()->md;
$ai = new ShortNamer(AnnotatedInterface::class);
$ma = new ShortNamer(MetaAnnotation::class);
$pe = new ShortNamer(ParamsExpander::class);
$m = new ShortNamer(Meta::class);
$mt = new ShortNamer(MetaType::class);
$mm = new ShortNamer(MetaMethod::class);
$mp = new ShortNamer(MetaProperty::class);
/* @var $ma MetaAnnotation */
?>
<title>Creating Annotations</title>

# Creating Annotations

To make annotation engine recognize annotations and
react on them, for each annotation separate class should be created.

<p class="alert alert-success">
    Each annotation class name should end with <code>Annotation</code>,
    however when annotating classes this suffix should be omitted.
</p>

This class should extend from <?= $ma; ?> and must define <?= $ma->init(); ?> method
as well should define propery `value` with default of `null`. It is
important to define `value` as it might be later used with <?= $pe; ?>.

<p class="alert alert-success">
    It is annotation class responsibility to set entity value
</p>

Annotation should set entity value, so that the annotation itself
will decide on what to do if it is called multiple time, or
how to handle incoming data. When extending from <?= $ma; ?>,
annotation <?= $ma->init(); ?> method will have available
methods for obtaining and working with metadata:

* <?= $ma->getEntity(); ?> - returns entity on which current annotation is
defined - this might be of type:
  * <?= $mt; ?> - containing class (type) metadata, ie annotations defined
above `class` keyword
  * <?= $mm; ?> - metadata for method
  * <?= $mm; ?> - metadata for property
* <?= $ma->getMeta(); ?> - returns whole <?= $m; ?> container, allowing
to obtain for example type information from method or property annotation.

<p class="alert alert-success">
    It is possible to <a href="../../build-in/">limit where annotation can be placed</a>
    or which other annotation our annotation <a href="../../build-in/">should avoid</a>
</p>

### Example `@Label` annotation class

```
namespace Maslosoft\AddendumTest\Annotations;

class LabelAnnotation extends \Maslosoft\Addendum\Collections\MetaAnnotation
{

    public $value;

    public function init()
    {
        $this->getEntity()->label = (string) $this->value;
    }

}
```

See also [this repository][guide] and [blog post][post] for extra details.

[guide]: https://github.com/MaslosoftGuides/addendum.first-annotation
[post]: https://maslosoft.com/blog/2017/03/27/creating-your-first-php-annotation-with-addendum/
[limit]: ../../build-in/
[a]: ../
