<p style="font-weight: bold; font-size: 1.2em"><?= $data->name?></p>
<p style="font-weight: bold; font-size: 1.1em">Description</p>
<p><?= str_replace($data->name, "<code>$data->name</code>", $data->description);?></p>
<p style="font-weight: bold; font-size: 1.1em">Example</p>
<pre><code>
/**
 * <?= $data->name?><?= $params . "\n";?>
 */
public $items = [];
</code></pre>