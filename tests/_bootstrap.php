<?php

use Maslosoft\Addendum\Addendum;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\Signals\Signal;
use Maslosoft\Signals\Utility;

error_reporting(E_ALL);
date_default_timezone_set('Europe/Paris');

define('VENDOR_DIR', __DIR__ . '/../vendor');
//define('YII_DIR', VENDOR_DIR . '/yiisoft/yii/framework/');
require VENDOR_DIR . '/autoload.php';

// Invoker stub for windows
if (defined('PHP_WINDOWS_VERSION_MAJOR'))
{
	require __DIR__ . '/misc/Invoker.php';
}

//require $yii = YII_DIR . 'yii.php';

//require_once YII_DIR . 'base/CComponent.php';
//require_once YII_DIR . 'base/CModel.php';

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/misc/AddendumWipe.php';
require_once __DIR__ . '/misc/NonNamespaced.php';
require_once __DIR__ . '/misc/NonNamespacedAnnotation.php';

define('TEST_CONSTANT', 'Global constant');

define('ANNOTATIONS_PATH', __DIR__ . '/annotations');
define('MODELS_PATH', __DIR__ . '/models');
define('RUNTIME_PATH', __DIR__ . '/../runtime');

$signal = new Signal();
$signal->runtimePath = RUNTIME_PATH;
$signal->paths = [ANNOTATIONS_PATH, MODELS_PATH];
$signal->init();
$signal->resetCache();

$addendum = new Addendum();
$addendum->namespaces[] = NamespacedAnnotation::Ns;
$addendum->init();
