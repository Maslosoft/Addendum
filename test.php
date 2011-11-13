<?php
require_once(dirname(__FILE__) . '/annotation_parser.php');
function __autoload($class)
{
	require_once "./$class.php";
}
class Table extends EAnnotation
{
	public $name;
	public $test;
}
/**
 * @Table(name = "table", test = "none")
 */
class Annotated
{

}
class Mc
{
	private $time = '';
	public function __construct()
	{
		$this->time = microtime(true);
	}
	public function __destruct()
	{
		echo '<br>';
		echo number_format(microtime(true) - $this->time, 6);
	}
}
$t = new Mc;
$info = new ReflectionAnnotatedClass('Annotated');
echo $info->getAnnotation('Table')->name;