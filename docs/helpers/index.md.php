<?php

use Maslosoft\Ilmatar\Widgets\MsWidget;
use Maslosoft\Staple\Widgets\SubNavRecursive;
?>
<?php

/* @var $this MsWidget */
?>
<title>6. Helpers</title>

# Helpers

Helpers are meant to help creating new annotations.

### Helpers:

<?php

echo new SubNavRecursive([
'root' => __DIR__,
 'path' => '.',
 'skipLevel' => 1,
]);
?>