<?php

use Maslosoft\Addendum\Addendum;
use Maslosoft\Addendum\Utilities\ClassChecker;
use Maslosoft\AddendumTest\Annotations\NamespacedAnnotation;
use Maslosoft\Signals\Signal;
use Maslosoft\Signals\Utility;

error_reporting(E_ALL ^ E_DEPRECATED);
date_default_timezone_set('Europe/Paris');

const VENDOR_DIR = __DIR__ . '/../vendor';
require VENDOR_DIR . '/autoload.php';

// Invoker stub for windows
if (defined('PHP_WINDOWS_VERSION_MAJOR'))
{
	require __DIR__ . '/misc/Invoker.php';
}

$config = require __DIR__ . '/config.php';

require_once __DIR__ . '/misc/AddendumWipe.php';
require_once __DIR__ . '/misc/NonNamespaced.php';
require_once __DIR__ . '/misc/NonNamespacedAnnotation.php';

const TEST_CONSTANT = 'Global constant';

const ANNOTATIONS_PATH = __DIR__ . '/annotations';
const MODELS_PATH = __DIR__ . '/models';
const RUNTIME_PATH = __DIR__ . '/../runtime';

if(ClassChecker::exists(Signal::class))
{
	$signal = new Signal();
	$signal->runtimePath = RUNTIME_PATH;
	$signal->paths = [ANNOTATIONS_PATH, MODELS_PATH];
	$signal->init();
	$signal->resetCache();
}

$addendum = new Addendum();
$addendum->namespaces[] = NamespacedAnnotation::Ns;
$addendum->init();

// Here you can initialize variables that will be available to your tests
echo "PHP " . PHP_VERSION . PHP_EOL;
echo "Addendum " . $addendum->getVersion() . PHP_EOL;
error_reporting(E_ALL);