<?php

use Maslosoft\Addendum\Annotations\TargetAnnotation;
use Maslosoft\Addendum\Helpers\ParamsExpander;
use Maslosoft\Zamm\Iterator;
use Maslosoft\Zamm\DocBlock;
use Maslosoft\Zamm\ShortNamer;

?>
<?php
ShortNamer::defaults()->md;
$pe = new ShortNamer(ParamsExpander::class);
/* @var $pe ParamsExpander */
?>
<title>6. Params Expander</title>

# Params Expander

Params expander should be used inside annotation `init` method. Annotation
should have empty default values for this to work. The annotation should
also have `value` property containing empty array.

The <?= $pe; ?> class has method <?= $pe->expand(); ?> for easier
extracting parameters from annotation `value`.

If value have numeric keys, these will be mapped to params in order.

The method <?= $pe->expand(); ?> accepts (current) annotation
as a first parameter, and second parameter should contain
property names to check for.

<?= $pe->expand(); ?> will return hashmap containing keys and values.

### Example usage

```
class I18NAnnotation extends MetaAnnotation
{

	public $value = [];

	public $allowDefault = null;

	public $allowAny = null;

	public function init()
	{
		$data = ParamsExpander::expand($this, ['allowDefault', 'allowAny']);
	}
}
```