<p style="font-weight: bold; font-size: 1.2em"><?= $data->name?></p>
<p style="font-weight: bold; font-size: 1.1em">Description</p>
<p><?= nl2br(str_replace($data->name, "<code>$data->name</code>", str_replace("\r", "", $data->description)), true);?></p>
<p style="font-weight: bold; font-size: 1.1em">Example</p>
<pre><code>
/**
 * <?= $data->name?><?= $params . "\n";?>
 */
public $items = [];
</code></pre>